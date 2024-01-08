<?php

namespace App\Mail;

use Illuminate\{
    Mail\Mailable,
    Mail\Mailables\Content,
    Mail\Mailables\Envelope,
    Mail\Mailables\Address,
    Queue\SerializesModels,
    Bus\Queueable
};
class SendTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $create_users_url)
    {
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('quickstart@example.com','Kenta Tanaka'),
            subject: '仮登録完了メール',
        );
    }
    public function content(): Content
    {
        return new Content(
            view: 'emails.mailable',
        );
    }
    public function attachments(): array
    {
        return [];
    }
}
