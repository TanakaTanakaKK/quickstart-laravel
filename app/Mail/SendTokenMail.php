<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\{
    Mailable,
    Mailables\Content,
    Mailables\Envelope,
    Mailables\Address,
};
use Illuminate\Queue\SerializesModels;

class SendTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $create_users_url)
    {
    
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('quickstart@example.com','Kenta Tanaka'),
            subject: '仮登録完了メール',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.mailable',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
