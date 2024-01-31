<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{
    Content,
    Envelope
};
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $password_reset_token)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'パスワード再設定メール',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset_password_mail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
