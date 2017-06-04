<?php

namespace Modules\EmailCampaigns\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Modules\EmailCampaigns\Http\Models\Email;
use Modules\Forms\Http\Models\Form;
use \Platform\Controllers\Core;

class SendMailing implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;

    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
      $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $variant = 1;
      $view = 'public.emails::' . Core\Secure::staticHash($this->email->user_id) . '.' . Core\Secure::staticHash($this->email->email_campaign_id, true) . '.' . $this->email->local_domain . '.' . $variant . '.index';

      // Set user table
      $tbl_name = 'x_form_entries_' . $this->email->user_id;
      $Entry = new \Modules\Forms\Http\Models\Entry([]);
      $Entry->setTable($tbl_name);

      // Loop through all forms
      $email_increment = 0;
      foreach ($this->email->forms as $form) {

        // Get confirmed entries
        $form_entries = $Entry->where('form_id', $form->id)->where('confirmed', 1)->orderBy('created_at', 'desc')->get();

        if (count($form_entries) > 0) {
          foreach ($form_entries as $form_entry) {
            $tag = $this->email->user_id . '_' . $form->id . '_' . $this->email->id . '_' . $form_entry->id;

            // Parse email and subject
            $html = \Modules\EmailCampaigns\Http\Controllers\FunctionsController::parseEmail($form_entry->email, $view, $form);
            $subject = \Modules\EmailCampaigns\Http\Controllers\FunctionsController::parseString($form_entry->email, $this->email->subject, $form);

            $response = \Mailgun::raw($html, function ($message) use ($subject, $form_entry, $tag) {
              $message
                ->subject($subject)
                ->from($this->email->emailCampaign->mail_from, $this->email->emailCampaign->mail_from_name)
                ->replyTo($this->email->emailCampaign->mail_from, $this->email->emailCampaign->mail_from_name)
                ->to($form_entry->email)
                ->tag($tag)
                ->trackClicks(true)
                ->trackOpens(true);
            });

            // Increment email sent
            \DB::table($tbl_name)
              ->where('id', $form_entry->id)
              ->update([
                'sent' => $form_entry->sent + 1,
                'last_send' => date('Y-m-d H:i:s')
              ]
            );

            $email_increment++;
          }

          // Increment
          \DB::table('forms')->whereId($form->id)->increment('sent', 1);
        }
      }

      // Increment
      \DB::table('emails')->whereId($this->email->id)->increment('sent', $email_increment);
    }
}
