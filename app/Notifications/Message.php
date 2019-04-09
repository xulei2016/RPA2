<?php

namespace App\Notifications;

use App\Models\Admin\Base\SysMessage;
use App\Models\Admin\Base\SysMessageTypes;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Message extends Notification implements ShouldQueue
{
    use Queueable;

    public $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->data = $data;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast','database'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message_id' => $this->data['id'],
            'title' =>$this->data['title'],
            'content' => $this->data['content'],
            'mode' => $this->data['mode'],
            'typeName' => $this->data['type']
        ]);
    }

    public function toDatabase($notifiable)
    {
        // 存入数据库里的数据
        return [
            'message_id' => $this->data['id'],
            'title' =>$this->data['title'],
            'content' => $this->data['content'],
            'mode' => $this->data['mode'],
            'typeName' => $this->data['type']
        ];
    }


}
