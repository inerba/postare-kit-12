<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $name;

    private string $email;

    private string $body;

    private ?string $phone;

    private string $url;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $email, string $body, ?string $phone = null, ?string $url = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->body = $body;

        $this->phone = $phone;

        // Evita URL di Livewire
        if ($url === null) {
            // Controllo se l'URL corrente è un URL di Livewire
            $currentUrl = function_exists('url') ? url()->current() : '';
            if (! str_contains($currentUrl, '/livewire/')) {
                $url = $currentUrl;
            } else {
                // Utilizza il referer come fallback
                $url = Request::server('HTTP_REFERER', '');
            }
        }

        $this->url = $url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(db_config('general.mail_from_email'), db_config('general.mail_from_name')),
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
                'phone' => $this->phone,
                'url' => $this->url,
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
