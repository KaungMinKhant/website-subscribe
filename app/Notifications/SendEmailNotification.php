<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Mail;

class SendEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->toMail($notifiable);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $notifiable;
        $subscribed_websites = json_decode($user->subscribed_websites, TRUE);
        foreach ($subscribed_websites as $subscribed_website)
        {
            $posts = Post::where('website_id', $subscribed_website)->get();   
            foreach ($posts as $post){
                if (is_null($user->seen_posts))
                {                       
                    $user->seen_posts = array((int)$post->id);
                    $user->save();
                    return (new MailMessage)
                      ->line("New Post => {$post->name}")
                      ->line("Description => {$post->description}");
                }
                else {
                    if (is_array($user->seen_posts))
                    {
                        $seen_posts = $user->seen_posts;
                    }
                    else
                    {
                        $seen_posts = json_decode($user->seen_posts, TRUE);
                    }
                    if (in_array($post->id, $seen_posts))
                    {
                        
                    }
                    else 
                    {
                        array_push($seen_posts, (int)$post->id);
                        $user->seen_posts = $seen_posts;
                        $user->save();
                        return (new MailMessage)
                          ->line("New Post => {$post->name}")
                          ->line("Description => {$post->description}");
                    }
                }
            }
        }
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
