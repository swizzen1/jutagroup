<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscribe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscribesController extends BaseController
{
    public $data = []; // წარმოდგენის ფაილებზე მისამაგრებელი ინფორმაცია
    private $model; // მიმდინარე ინსტანციის მოდელი
    private $views_folder; // წარმოდგენების ფაილების საქაღალდე  მიმდინარე ინსტანციისათვის
    private $main_table; // მიმდინარე ინსტანციის ძირითადი ცხრილი
    
    /*
    * მარშრუტების სუფიქსი მიმდინარე ინსტანციისათვის, გამოიყენება ბმულების 
    * გენერირებისათვის კონტროლერებსა და წარმოდგენის ფაილებში. ძირითადი პრეფიქსები, 
    * რომლებიც დაერთვის: 'Add', 'Store', 'Edit', 'Update', 'Remove', 'Status', 'Ordering'
    */
    private $routes_suffix;     
    
    public function __construct(Subscribe $model)
    {
        parent::__construct();
        
        $this->model = $model;
        $this->routes_suffix = 'Subscribes';
        $this->views_folder = 'Administrator.subscribes'; 
        $this->main_table = 'subscribes';
    }
    
    public function index()
    {
        $this->data['listing_columns'] = ['email','created_at']; // <th> ელემენტები
        $this->data['items'] = $this->model->allItems();
        $this->data['routes_suffix'] = $this->routes_suffix;
        $this->data['main_table'] = $this->main_table;
        
        return view($this->views_folder.'.index',$this->data);
    }    
}
