<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function homeView()
    {
        return view('client.index');
    }
}
