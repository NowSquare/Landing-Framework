<?php

namespace Modules\EmailCampaigns\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Modules\EmailCampaigns\Http\Models\Email;
use Modules\Forms\Http\Models\Form;
use \Platform\Controllers\Core;

class SendEmail implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;

    protected $mailto;
    protected $email;
    protected $form;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailto, Email $email, Form $form)
    {
      $this->mailto = $mailto;
      $this->email = $email;
      $this->form = $form;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $data = [
        'email_text_version' => ''
      ];

      $variant = 1;
      $view = 'public.emails::' . Core\Secure::staticHash($this->email->user_id) . '.' . Core\Secure::staticHash($this->email->email_campaign_id, true) . '.' . $this->email->local_domain . '.' . $variant . '.index';

      $html = \Modules\EmailCampaigns\Http\Controllers\FunctionsController::parseEmail($this->mailto, $view, $this->form);
      $subject = \Modules\EmailCampaigns\Http\Controllers\FunctionsController::parseString($this->mailto, $this->email->subject, $this->form);

      // Get entry id
      $entry_id = 0;
      $tbl_name = 'x_form_entries_' . $this->form->user_id;

      $Entry = new \Modules\Forms\Http\Models\Entry([]);
      $Entry->setTable($tbl_name);

      $form_entry = $Entry->where('form_id', $this->form->id)->where('email', $this->mailto)->orderBy('created_at', 'desc')->first();

      if (! empty($form_entry)) {
        $entry_id = $form_entry->id;
      }

      $tag = $this->email->user_id . '_' . $this->form->id . '_' . $this->email->id . '_' . $entry_id;

      // Increment email sent
      \DB::table('emails')->whereId($this->email->id)->increment('sent');

      $response = \Mailgun::raw($html, function ($message) use ($subject, $tag) {
        $message
          ->subject($subject)
          ->from($this->email->emailCampaign->mail_from, $this->email->emailCampaign->mail_from_name)
          ->replyTo($this->email->emailCampaign->mail_from, $this->email->emailCampaign->mail_from_name)
          ->to($this->mailto)
          ->tag($tag)
          ->trackClicks(true)
          ->trackOpens(true);
      });
    }
}
