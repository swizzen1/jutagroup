<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsesController extends BaseController
{
    public function index()
    {
        return view('Administrator.newses.index');
    }    
}
