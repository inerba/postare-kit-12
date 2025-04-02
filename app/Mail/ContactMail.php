<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $name;
    private string $email;
    private string $body;
    private array|null $custom_fields = null;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $email, string $body, array|null $custom_fields = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->body = $body;
        $this->custom_fields = $custom_fields;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(db_config('general.mail_from_email'), db_config('general.mail_from_name')),
            to: [
                new Address(db_config('general.mail_to_email'), db_config('general.mail_to_name')),
            ],
            replyTo: [
                new Address($this->email, $this->name),
            ],
            subject: db_config('general.mail_subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'body' => $this->body,
                'custom_fields' => $this->custom_fields,
            ],
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
