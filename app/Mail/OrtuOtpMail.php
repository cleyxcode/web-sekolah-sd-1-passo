<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrtuOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpCode;

    public function __construct($otpCode)
    {
        $this->otpCode = $otpCode;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Reset Password Portal Orang Tua',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ortu-otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
