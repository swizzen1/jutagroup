<?php

namespace App\Http\Livewire\Client\About;

use Livewire\Component;

class AboutIndex extends Component
{
    public function render()
    {
        $metaTitle = trans('menu.about');
        return view('livewire.client.about.about-index', compact('metaTitle'))->extends('layouts.client');
    }
}
