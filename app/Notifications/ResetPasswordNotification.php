<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param object $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
{
    $resetUrl = url(config('app.url').route('password.reset', $this->token, false));
    $count = config('auth.passwords.users.expire'); // or any other way you want to define the expiration time
    $email = $notifiable->getEmailForPasswordReset(); // Get the email associated with the notifiable

    return (new MailMessage)
        ->subject('Password Reset') // Set the email subject
        ->view('emails.password-reset', ['token' => $this->token, 'resetUrl' => $resetUrl, 'count' => $count, 'email' => $email]);
}




    /**
     * Get the array representation of the notification.
     *
     * @param object $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
