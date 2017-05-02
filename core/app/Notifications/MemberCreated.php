<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MemberCreated extends Notification
{
    use Queueable;

    public $password;
    public $domain;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password, $domain)
    {
        $this->password = $password;
        $this->domain = $domain;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                  ->subject('[' . config('app.name', 'Platform') . '] ' . trans('global.member_registration_subject'))
                  ->greeting(trans('global.mail_greeting', ['name' => $notifiable->name]))
                  ->line(trans('global.member_registration_mail_line1', ['domain' => $this->domain, 'email' => $notifiable->email, 'password' => $this->password]))
                  ->line(trans('global.member_mail_closing'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
