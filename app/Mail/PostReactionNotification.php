<?php

namespace App\Mail;

use App\Models\Post\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostReactionNotification extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Post */
    protected $post;

    /**
     * Create a new message instance.
     *
     * @param  PostReaction $order
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Jauna reakcija',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails.postreact',
            with: [
                'username' => $this->post->user->name,
                'totalLIKE' => $this->post->totalLIKE,
                'link' => url("posts/view/{$this->post->id}")
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
