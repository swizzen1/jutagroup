<?php

namespace App\Http\Livewire\Client\Contact;

use App\Mail\ok;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class ContactIndex extends Component
{
    public $username, $email, $subject, $message;
    public function render()
    {
        $metaTitle = trans('menu.contact');
        return view('livewire.client.contact.contact-index', compact('metaTitle'))->extends('layouts.client');
    }

    public function sendMail()
    {
        Mail::to(['giorgibostoganashvili0@gmail.com'])->send(new ok());
    }
}
