<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendApprovalNotificationToAdminForOfflinePayment extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $user;
    public $package;
    public function __construct($user, $package)
    {
        $this->user = $user;
        $this->package = $package;
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
        $details = 'Package: ' . $this->package->name . ', Package Price: ' . $this->package->package_price . ', Paid Via: ' . $this->package->paid_via;

        return (new MailMessage)
                ->greeting('Hello!')
                ->subject('New Subscription')
                ->line('New package has been subscribed by ' . $this->user->name)
                ->line($details);
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
