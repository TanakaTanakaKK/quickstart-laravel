<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{
    Content,
    Envelope,
};
use Illuminate\Queue\SerializesModels;

class SendTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $authentication_token)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '仮登録完了メール',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.authentication_mail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}