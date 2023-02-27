<?php

namespace App\Http\Controllers\Admin;

use DB;
use App;
use Session;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class ReviewsController extends BaseController
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
    
    public function __construct(Review $model)
    {
        parent::__construct();
        
        $this->model = $model;
        $this->routes_suffix = 'Reviews';
        $this->views_folder = 'Administrator.products.reviews'; 
        $this->main_table = 'product_reviews';
    }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['listing_columns'] = ['first_name','last_name','comment'];
        $this->data['items'] = $this->model::allItems($this->configuration->admin_lang, $status_on=false);
        $this->data['routes_suffix'] = $this->routes_suffix;
        $this->data['main_table'] = $this->main_table;
       
        return view($this->views_folder.'.index',$this->data);
    }  
    
    /**
     * ჩანაწერების წაშლა 
     * 
     * @param  $request მომხმარებლის მიერ გაგზავნილი მოთხოვნის აბსტრაქციის - Request კლასის ფსევდონიმი.
     * 
     * @return status : 1 => წარმატება,  2 => შეცდომა, 5 => უფლება არ აქვს
     * 
     */
    protected function remove(Request $request)
    {
        $id = $request->id;
        $table = $request->table;
        $item = DB::table($table)->find($id);

        if(!$item)
        {
            return response()->json([
                'status' => 2, 
                'type' => 'error',
                'text' => trans('admin.error')
            ]);
        }
        
        $delete = DB::table($table)->where('id', $id)->delete();
        
        if(!$delete)
        {
            return response()->json([
                'status' => 2, 
                'type' => 'error',
                'text' => trans('admin.error')
            ]);
        }
        
        $stars = 0;
        $reviews = DB::table('product_reviews')->where('product_id',$item->product_id)->get();
        
        if($reviews->count() > 0)
        {
            foreach($reviews as $review)
            {
                $stars += $review->stars;
            }
            
            DB::table('products')->where('id',$item->product_id)->update([
                'rate' => round($stars / $reviews->count())
            ]);
        }
        else
        {
            DB::table('products')->where('id',$item->product_id)->update(['rate' => null]);
        }
        
        return response()->json([
            'status' => 1, 
            'type' => 'success',
            'text' => trans('admin.success')
        ]);        
    }  
}


