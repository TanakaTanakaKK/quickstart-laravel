<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $reset_password_token)
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
