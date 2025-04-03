<?php

namespace App\Livewire\Mason;

use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Form extends Component
{
    public $name;

    public $phone;

    public $email;

    public $body;

    public $url;

    public $contactFormSubmitted = false;

    public $errorMessage = false;

    public $gdprConsent = false;

    public $mail_to;

    public function mount()
    {
        if (empty($this->mail_to)) {
            $this->mail_to = db_config('general.mail_to_email');
        }
    }

    public function sendEmail()
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

        $this->errorMessage = false;

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

            return false;
        }

        $this->contactFormSubmitted = true;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->body = '';
        $this->url = '';
        $this->gdprConsent = false;
    }

    public function render()
    {
        return view('livewire.mason.form', [
            'contactFormSubmitted' => $this->contactFormSubmitted,
            'errorMessage' => $this->errorMessage,
        ]);
    }
}
