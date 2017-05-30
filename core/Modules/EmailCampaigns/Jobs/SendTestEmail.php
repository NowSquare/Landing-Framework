<?php

namespace Modules\EmailCampaigns\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

/*
sude nohup php artisan queue:work --daemon --tries=3
 */

class SendTestEmail implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;

    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email)
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

      $data = [
        'text' => 'This is the text version',
        'var1' => 'val1'
      ];

      $response = \Mailgun::send(['template.emails::opt-in.index', 'template.emails::_text.index'], $data, function ($message) {
        $message
          ->subject('Mailgun test mail')
          ->from('noreply@landingframework.com', 'Sembo')
          ->replyTo('noreply@landingframework.com', 'Sembo')
          ->to('info@s3m.nl', 'Sem')
          ->trackClicks(true)
          ->trackOpens(true);
      });

    }
}
