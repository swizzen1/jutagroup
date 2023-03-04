<?php

namespace App\Http\Livewire\Client\Contact;

use App\Mail\ok;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactIndex extends Component
{
    public $username;

    public $email;

    public $subject;

    public $message;

    public function rules()
    {
        return [
            'username' => 'required',
            'email' => 'required|email',
            'subject' => 'required|max:55',
            'message' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'სახელი სავალდებულოა',
            'email.required' => 'Email სავალდებულოა',
            'subject.required' => 'თემა სავალდებულოა',
            'subject.max' => 'თემა უნდა შეიცავდეს მაქსიმუმ 55 სიმბოლოს',
            'message.required' => 'შეტყობინება სავალდებულოა',
        ];
    }

    public function render()
    {
        $metaTitle = trans('menu.contact');

        return view('livewire.client.contact.contact-index', compact('metaTitle'))->extends('layouts.client');
    }

    public function sendMail()
    {
        $this->validate();
        // Mail::to(config())->send(new ok($this->username, $this->subject, $this->message));
    }
}
