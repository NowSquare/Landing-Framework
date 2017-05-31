<?php

namespace Modules\EmailCampaigns\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Modules\EmailCampaigns\Http\Models\Email;
use \Platform\Controllers\Core;

class SendTestEmail implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;

    protected $mailto;
    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailto, Email $email)
    {
        $this->mailto = $mailto;
        $this->email = $email;
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

      $html = \Modules\EmailCampaigns\Http\Controllers\FunctionsController::parseEmail($this->mailto, $view, $this->email);

      $response = \Mailgun::raw($html, function ($message) {
        $message
          ->subject($this->email->subject)
          ->from($this->email->emailCampaign->mail_from, $this->email->emailCampaign->mail_from_name)
          ->replyTo($this->email->emailCampaign->mail_from, $this->email->emailCampaign->mail_from_name)
          ->to($this->mailto)
          ->trackClicks(true)
          ->trackOpens(true);
      });

    }
}
