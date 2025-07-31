<?php

namespace App\Livewire\Mason;

use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Form extends Component
{
    public string $name;

    public string $phone;

    public string $email;

    public string $body = '';

    public string $url;

    public bool $contactFormSubmitted = false;

    /**
     * Messaggio di errore per la form.
     */
    public ?string $errorMessage = null;

    public bool $gdprConsent = false;

    public string $mail_to;

    public function mount(): void
    {
        if (empty($this->mail_to)) {
            $this->mail_to = db_config('general.mail_to_email');
        }
    }

    public function sendEmail(): void
    {
        // Indirizzo dal quale proviene il form
        $this->url = url()->previous();

        // Validate input
        $this->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'body' => 'required', // Corretto da 'message' a 'body'
            'gdprConsent' => 'required',
        ]);

        $this->errorMessage = null;

        try {
            Mail::to($this->mail_to)
                ->send(new ContactMail(
                    $this->name,
                    $this->email,
                    $this->body,
                    $this->phone,
                    $this->url
                ));
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }

        $this->contactFormSubmitted = true;
        $this->resetFields();
    }

    public function resetFields(): void
    {
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->body = '';
        $this->url = '';
        $this->gdprConsent = false;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.mason.form', [
            'contactFormSubmitted' => $this->contactFormSubmitted,
            'errorMessage' => $this->errorMessage,
        ]);
    }
}
