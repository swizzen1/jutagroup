<?php

namespace App\Http\Controllers\Admin;

use DB;
use Session;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class SalesController extends BaseController
{
    public $data = []; // წარმოდგენის ფაილებზე მისამაგრებელი ინფორმაცია
    private $model; // მიმდინარე ინსტანციის მოდელი
    private $views_folder; // წარმოდგენების ფაილების საქაღალდე  მიმდინარე ინსტანციისათვის
    private $main_table; // მიმდინარე ინსტანციის ძირითადი ცხრილი
    private $translates_table; // მიმდინარე ინსტანციის სათარგმნი ცხრილი    
    
    /*
    * მარშრუტების სუფიქსი მიმდინარე ინსტანციისათვის, გამოიყენება ბმულების 
    * გენერირებისათვის კონტროლერებსა და წარმოდგენის ფაილებში. ძირითადი პრეფიქსები, 
    * რომლებიც დაერთვის: 'Add', 'Store', 'Edit', 'Update', 'Remove', 'Status', 'Ordering'
    */
    private $routes_suffix;     
    
    public function __construct(Sale $model)
    {
        parent::__construct();
        
        $this->model = $model;
        $this->routes_suffix = 'Sales';
        $this->views_folder = 'Administrator.products.sales'; 
        $this->main_table = 'sales';
        $this->translates_table = 'sales_translates';
    }
    
    public function main_columns()
    {
        // ძირითადი ცხრილის ყველა ველი
        $main_table_columns = Schema::getColumnListing($this->main_table); // All colums of main table

        /* ძირითადი ცხრილის ის ველები, რომელთა შესაბამისი html 
         * ელემენტების ავტოდაგენერირებაც არ გვინდა წარმოდგენის ფაილში
         */
        $main_no_generate_columns = [
            'id',
            'product_id',
            'finish',
            'image',
            'sort',
            'show_on_index',
            'status',
            'created_at',
            'updated_at'
        ];
        
        /* ძირითადი ცხრილის ის ველები, რომელთა შესაბამისი html 
         * ელემენტების ავტოდაგენერირებაც გვინდა წარმოდგენის ფაილში
         */
        return  array_diff($main_table_columns,$main_no_generate_columns);
    }
    
    public function translate_columns()
    {
        if(property_exists(__CLASS__, 'translates_table'))
        {
            $translates_table_columns = Schema::getColumnListing($this->translates_table); 

            $translates_no_generate_columns = [
                'id',
                'parent_id',
                'lang',
                'created_at',
                'updated_at'
            ];

            return array_diff($translates_table_columns,$translates_no_generate_columns);   
        }
        else
        {
            return [];
        }        
    }
    
    protected $required_columns = ['product_id','image','title','finish'];
    
    public function index()
    {
        $this->data['listing_columns'] = ['sort', 'status','show_on_index','image','title','finish']; // <th> ელემენტები
        $this->data['items'] = $this->model->allItems($this->configuration->admin_lang, $status_on = false);
        $this->data['routes_suffix'] = $this->routes_suffix;
        $this->data['main_table'] = $this->main_table;
        $this->data['translates_table'] = $this->translates_table;
        
        return view($this->views_folder.'.index',$this->data);
    }
    
    public function create()
    {
        $this->data['routes_suffix'] = $this->routes_suffix;
        $this->data['main_columns'] = $this->main_columns();
        $this->data['required_columns'] = $this->required_columns;
        $this->data['translate_columns'] = $this->translate_columns(); 
        
        return view($this->views_folder.'.add', $this->data);
    }

    public function store(Request $request)
    {   
        $this->validate($request,[
            'translates.'.$this->configuration->admin_lang.'.title' => 'required',
            'finish' => 'required',
            'product_id' => 'required|numeric',
            'image' => 'required|mimes:jpeg,jpg,png',
        ]);  
        
        $insert = $this->model->addItem($request);
        
        $request->session()->flash('last_edited_lang', $request->last_edited_lang);

        if(!$insert) 
        {
            $request->session()->flash('error', true);
            return redirect()->route($this->routes_suffix);
        }

        $request->session()->flash('success', true);
        
        return redirect()->route($this->routes_suffix);
    }

    public function edit($id)
    {
        $item = $this->model->find($id);
        
        if(!$item) 
        {
            return redirect()->back();
        }
        
        $attached_product = Product::getItemInfo($item->product_id, $this->configuration->admin_lang);
        
        $this->data['routes_suffix'] = $this->routes_suffix;
        $this->data['main_columns'] = $this->main_columns();
        $this->data['required_columns'] = $this->required_columns;
        $this->data['translate_columns'] = $this->translate_columns(); 
        $this->data['item'] = $item;
        $this->data['attached_product'] = $attached_product;
        $this->data['model'] =  $this->model;
        
        return view($this->views_folder.'.edit',$this->data);
    }

    public function update(Request $request, $id)
    {
        $item = $this->model->find($id);

        if(!$item) 
        {
            return redirect()->back();
        }
        
        $this->validate($request,[
            'translates.'.$this->configuration->admin_lang.'.title' => 'required',
            'finish' => 'required',
            'product_id' => 'required|numeric',
            'image' => 'mimes:jpeg,jpg,png',
        ]);     
       
        $update = $this->model->updateItem($request , $item);
        
        $request->session()->flash('last_edited_lang', $request->last_edited_lang);

        if(!$update) 
        {
            $request->session()->flash('error', true);
            return redirect()->route('Edit'.$this->routes_suffix, $id);
        }

        $request->session()->flash('success', true);
        
        if($request->stay)
        {
            return redirect()->route('Edit'.$this->routes_suffix, $id);
        }
        else
        {
           return redirect()->route($this->routes_suffix);
        }        
    }  
}
