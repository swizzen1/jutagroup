<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Review extends Model 
{
    protected $fillable = ['image'];
    protected $table = 'product_reviews';
    private static $current_class = __CLASS__; 
    private static $main_table = 'product_reviews';

    public static function getItemInfo($id = 0, $local = '') 
    {
        return self::$current_class::where(self::$main_table.'.id', $id)->first();
    }

    public static function allItems($local = '', $status_on = false) 
    {
        return self::$current_class::select('*')
                ->join('products_translates', self::$main_table.'.product_id', 'products_translates.parent_id')
                ->where('products_translates.lang',$local)
                ->when($status_on, function ($query, $status_on) {
                    return $query->where(self::$main_table.'.status', $status_on);
                })
                ->select(self::$main_table.'.*', 'products_translates.title')
                ->orderBy('id','DESC')->paginate(10);        
    }
}
