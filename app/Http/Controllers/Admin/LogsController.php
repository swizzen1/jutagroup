<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogsController extends BaseController
{
    public function index()
    {
        return view('Administrator.logs.index');
    }
    
}
