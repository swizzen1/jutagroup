<?php

namespace App\Http\Livewire\Client\Home;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.client.home.index')->extends('layouts.client');
    }
}
