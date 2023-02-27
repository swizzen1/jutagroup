<?php

namespace App\Http\Controllers\Admin;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends BaseController
{
    public function index()
    {
        return view('Administrator.products.index');
    }
    
}
