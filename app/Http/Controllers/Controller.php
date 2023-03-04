<?php

namespace App\Http\Controllers;

use App;
use App\Models\Brand;
use App\Models\Information;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Seo;
use Cache;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use LaravelLocalization;
use Session;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $lang; // მიმდინარე ენა

    protected $seo_routes; // ის მარშრუტები, რომლებსაც ჭირდება SEO ტეგები

    protected $config; // json ფაილში შენახული კონფიგურაციული პარამეტრები : ინფორმაცია ქეშირებების შესახებ ...

    protected $available_langs; // ხელმისაწვდომი ენები

    protected $categories; // პირველი დონის კატეგორიები

    public function __construct(Request $request)
    {
        $this->lang = App::getLocale();
        $this->seo_routes = ['index', 'news', 'products'];
        $this->available_langs = LaravelLocalization::getSupportedLocales();
        $this->config = file_exists(public_path('config.json')) ?
                json_decode(file_get_contents(public_path('config.json')), true) : false;

        $this->middleware(function ($request, $next) {
            View::share('cart', session()->get('cart'));

            return $next($request);
        });

        // კატეგორიები
        if (Cache::has('product_categories')) {
            $categories = Cache::get('product_categories')[$this->lang];
        } else {
            $categories = ProductCategory::allItems($this->lang, $level = false, $status_on = true, $where_in = false);

            if ($categories->count()) {
                $max_level = $categories->sortBy('level')->last()->level;

                for ($level = $max_level; $level > 0; $level--) {
                    $current_level_cats = $categories->where('level', $level);
                    $next_level_cats = $categories->where('level', $level + 1);

                    foreach ($current_level_cats as $current_level_cat) {
                        $current_level_cat->childs = $next_level_cats->where('parent_id', $current_level_cat->id);
                    }
                }
            }
        }

        $this->categories = $categories;

        View::share('lang', $this->lang);
        View::share('brands', Cache::has('brands') ? Cache::get('brands')[$this->lang] : Brand::allItems($this->lang, $status_on = true));
        View::share('contact_info', Cache::has('informations') ? Cache::get('informations')[$this->lang] : Information::getItemInfo(3, $this->lang));
        View::share('categories', $categories);
    }

    // თუ კალათში დამატებული პროდუქტი წაშალა ადმინმა
    public function remove_from_cart_deleted_products()
    {
        $cart = Session::get('cart');

        if ($cart) {
            foreach ($cart as $prod_id => $qty) {
                $product = Product::find($prod_id);

                if (! $product || ! $product->status || ! $product->available) {
                    unset($cart[$prod_id]);
                }
            }

            Session::put('cart', $cart);
        }
    }

    public function get_cart_prices($percent, $delivery_price)
    {
        if (Session::has('cart')) {
            $result = [];
            $cart_items_total = 0;

            foreach (Session::get('cart') as $prod_id => $item_data) {
                $product = DB::table('products')->select('price')->where('id', $prod_id)->first();

                if ($product) {
                    $result['prices'][$prod_id] = $product->price;
                    $cart_items_total += $item_data['qty'] * $product->price;
                } else {
                    continue;
                }
            }

            $discount = ($cart_items_total / 100) * $percent;
            $discounted_total = $cart_items_total - $discount;
            $result['total'] = $discounted_total + $delivery_price;

            return $result;
        }

        return false;
    }

    public function get_seo($route = null)
    {
        if (Cache::has('seos')) {
            $seo = Cache::get('seos')[$this->lang]->firstWhere('route', $route);
        } else {
            $seo = Seo::getItemInfo($route, $this->lang);
        }

        return $seo;
    }
}
