<?php

namespace Modules\ModHairWorld\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\ModHairWorld\Channels\OneSignalChannel;

class CommonNotify extends Notification
{
    use Queueable;


    public $cover;
    public $title;
    public $link;
    public $color;
    public $mobile_title = '';
    public $type = 'text';
    public $url = '';
    public $data = [];
    public $mobile_heading = '';
    public $mobile_notification = true;
    public $overwrite = [];
    public $callback = null;

    public function __construct(
        $cover, $title, $link, $color, $mobile_notification = true,
                                $type = 'text', $mobile_heading = '', $mobile_title = false, $url = '',
                                $data = [],
                                $overwrite = [],
                                $callback = null
    )
    {
        $this->cover = $cover;
        $this->title = $title;
        $this->link = $link;
        $this->color = $color;
        $this->type = $type;
        $this->url = $url;
        $this->data = array_merge($data, [
            'notification_id' => $this->id
        ]);
        $this->mobile_heading = $mobile_heading;
        $this->mobile_notification = $mobile_notification;
        $this->overwrite = $overwrite;
        $this->callback = $callback;
        if($mobile_title){
            $this->mobile_title = $mobile_title;
        }
        else{
            $this->mobile_title = strip_tags($title);
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if($this->mobile_notification){
            return ['database', OneSignalChannel::class];
        }
        else{
            return ['database'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!');
    }



    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
//        public $type = 'text';
//        public $url = '';
//        public $data = [];
//        public $mobile_heading = '';
//        public $mobile_notification = true;
//        public $overwrite = [];
        return [
            'cover' => $this->cover,
            'link' => $this->link,
            'title' => $this->title,
            'color' => $this->color,
            'mobile_title' => $this->mobile_title,
            'type' => $this->type,
            'url' => $this->url,
            'data' => $this->data,
            'mobile_heading' => $this->mobile_heading,
            'mobile_notification' => $this->mobile_notification,
            'overwrite' => $this->overwrite
        ];
    }
}
