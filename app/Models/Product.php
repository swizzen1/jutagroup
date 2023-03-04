<?php

namespace App\Models;

use App\Traits\ActionLog;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Product extends Model
{
    use ActionLog;

    protected $guarded = [];

    private static $current_class = __CLASS__;

    private static $translates_class = 'App\Models\ProductsTranslate';

    private static $main_table = 'products';

    private static $translates_table = 'products_translates';

    private static $gallery_images_table = 'products_photos';

    public static function get_required_lang()
    {
        return DB::table('configurations')->select('admin_lang')->first()->admin_lang;
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredients::class, 'product_ingradients', 'product_id', 'ingredient_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductsPhoto', 'parent_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\ProductCategory');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\ProductReview', 'product_id');
    }

    public static function addItem($request)
    {
        $class_base_name = class_basename(self::$current_class);
        $item = new self::$current_class;
        $table_columns = Schema::getColumnListing(self::$main_table);
        $request_keys = $request->except([
            '_token',
            'translates',
            'image',
            'status',
            'new',
            'ingredients',
            'available',
            'color_images',
            'top_product',
        ]);

        $item->status = $request->status === 'on' ? 1 : 0;
        $item->top_product = $request->new === 'on' ? 1 : 0;
        $item->available = $request->available === 'on' ? 1 : 0;

        $max_sort = self::$current_class::max('sort');
        $item->sort = $max_sort ? ++$max_sort : 1;

        foreach ($request_keys as $key => $value) {
            if (in_array($key, $table_columns)) {
                $item->$key = $value;
            }
        }

        if ($request->hasFile('image')) {
            $data = [];
            $data['main_table'] = self::$main_table;
            $data['column_name'] = 'image';
            $data['upload_folder'] = $class_base_name;
            $data['item'] = $item;

            $upload_image = self::$current_class::uploadFile($request, $data);
        }

        if ($item->save()) {
            if (property_exists(__CLASS__, 'translates_table')) {
                // თარგმანების შემცველი ასცოციაციური მასივი ინდექსებით ka,en,ru ...
                $translates = $request->translates;
                // თარგმანების ცხრილის ველები
                $translates_table_columns = Schema::getColumnListing(self::$translates_table);

                foreach ($translates as $lang => $translation_data) {
                    $item_translate = new self::$translates_class();

                    /*
                     *  უშუალოდ თარგმანების მასივი [ველის_დასახელება => თარგმანი_შესაბამის_ენაზე]
                     *  $k : ველის დასახელება
                     *  $v : თარგმანი შესაბამის ენაზე
                     */
                    foreach ($translation_data as $k => $v) {
                        /*  თუ დამატების შაბლონში აღწერილია ისეთი ველი, რომლის 'name'
                         *  ატრიბუტის  შესაბამისი ველიც არ გვხვდება თარგმანების ცხრილში
                         */
                        if (! in_array($k, $translates_table_columns)) {
                            continue;
                        }

                        // თუ რომელიმე სათარგმნი ველი არ შეიყვანა აუცილებელი ენის გარდა რომელიმე სხვა ენაზე
                        if (! $v) {
                            // არაკრეფილის მნიშვნელობად ჩაჯდეს აუცილებელი ენის მნიშვნელობა
                            $item_translate->$k = $translates[self::get_required_lang()][$k];
                        } else {
                            $item_translate->$k = $v;
                        }
                    }

                    $item_translate->lang = $lang;
                    $item_translate->parent_id = $item->id;
                    $item_translate->save();
                }
            }

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $upload_gallery_image = self::$current_class::uploadGalleryImage($request, $file, $item, $class_base_name, self::$gallery_images_table);
                }
            }
            $item::storeLog($item, __CLASS__, 'Create');

            return true;
        }

        return false;
    }

    public static function updateItem($request, $item)
    {
        $class_base_name = class_basename(self::$current_class);
        $table_columns = Schema::getColumnListing(self::$main_table);
        $request_keys = $request->except([
            '_token',
            'translates',
            'image',
            'status',
            'new',
            'available',
            'color_images',
            'top_product',
        ]);
        $item->status = $request->status === 'on' ? 1 : 0;
        $item->top_product = $request->new === 'on' ? 1 : 0;
        $item->available = $request->available === 'on' ? 1 : 0;

        foreach ($request_keys as $key => $value) {
            if (in_array($key, $table_columns)) {
                $item->$key = $value;
            }
        }

        if ($request->hasFile('image')) {
            /*
            * როდესაც public_html საქაღალდეში მხოლოდ საჯარო ფაილებია,
            * საიტის ლოგიკა, კონფიგურაციული ფაილები და ა.შ კი მის გარეთ
            *
            */
            if (! is_null($item->image)) {
                if (file_exists('../public_html'.$item->image)) {
                    unlink('../public_html'.$item->image);
                }
            }

            /*
            // როდესაც მთლიანი საიტი public_html საქაღალდეშია
            if(file_exists(public_path($item->image)))
            {
               unlink(public_path($item->image));
            }
            *
            */

            $data = [];
            $data['main_table'] = self::$main_table;
            $data['column_name'] = 'image';
            $data['upload_folder'] = $class_base_name;
            $data['item'] = $item;

            $upload_image = self::$current_class::uploadFile($request, $data);
        }

        if ($item->update()) {
            if (property_exists(__CLASS__, 'translates_table')) {
                $translates = $request->translates;
                $translates_table_columns = Schema::getColumnListing(self::$translates_table);

                foreach ($translates as $lang => $translation_data) {
                    $item_translate = self::$translates_class::where('parent_id', $item->id)->where('lang', $lang)->first();

                    foreach ($translation_data as $k => $v) {
                        /*  თუ რედაქტირების შაბლონში აღწერილია ისეთი ველი, რომლის 'name'
                         *  ატრიბუტის  შესაბამისი ველიც არ გვხვდება თარგმანების ცხრილში
                         */
                        if (! in_array($k, $translates_table_columns)) {
                            continue;
                        }

                        if (! $v) {
                            $item_translate->$k = $translates[self::get_required_lang()][$k];
                        } else {
                            $item_translate->$k = $v;
                        }
                    }

                    $item_translate->update();
                }
            }

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $upload_gallery_image = self::$current_class::uploadGalleryImage($request, $file, $item, $class_base_name, self::$gallery_images_table);
                }
            }
            $item::storeLog($item, __CLASS__, 'Update');

            return true;
        }

        return false;
    }

    public static function getItemInfo($id = 0, $local = '', $status_on = false)
    {
        if (property_exists(__CLASS__, 'translates_table')) {
            /*
             * return DB::select("
                SELECT
                    products.*,
                    products_translates.title,
                    products_translates.meta_title,
                    products_translates.alt,
                    products_translates.short_description,
                    products_translates.description,
                    products_translates.meta_description,
                    pct.title AS cat_title,
                    IFNULL(B.reviews,0) AS reviews
                FROM products
                LEFT JOIN
                    (SELECT product_id, COUNT(id) AS reviews FROM product_reviews GROUP BY product_id) AS B
                ON products.id=B.product_id
                LEFT JOIN product_category_translates AS pct
                    ON products.category_id=pct.parent_id
                LEFT JOIN products_translates
                    ON products.id=products_translates.parent_id
                WHERE products.id=".$id." AND products_translates.lang='".$local."' AND pct.lang='".$local."' LIMIT 1");
             */

            return self::$current_class::join(self::$translates_table, self::$main_table.'.id', '=', self::$translates_table.'.parent_id')
                ->leftjoin('product_category_translates AS pct', self::$main_table.'.category_id', 'pct.parent_id')
                ->leftjoin('brands_translates AS bt', self::$main_table.'.brand_id', 'bt.parent_id')
                ->where(function ($query) use ($local) {
                    $query->where('bt.lang', $local)
                    ->orWhere('bt.lang', '=', null);
                })
                ->where(self::$main_table.'.id', $id)
                ->where(self::$translates_table.'.lang', $local)
                ->select(
                    self::$main_table.'.*',
                    self::$translates_table.'.title',
                    self::$translates_table.'.meta_title',
                    self::$translates_table.'.alt',
                    self::$translates_table.'.short_description',
                    self::$translates_table.'.description',
                    self::$translates_table.'.meta_description',
                    'pct.title AS cat_title',
                    'bt.title AS brand_title'
                )
                ->when($status_on, function ($query, $status_on) {
                    return $query->where(self::$main_table.'.status', $status_on);
                })
                ->first();
        } else {
            return self::$current_class::where(self::$main_table.'.id', $id)->first();
        }
    }

    public static function allItems($local = '', $status_on = false, $where_in = false, $where_in_cat = false, $paginate = false, $get = false)
    {
        return self::$current_class::join(self::$translates_table, self::$main_table.'.id', self::$translates_table.'.parent_id')
                ->leftjoin('product_category_translates AS pct', self::$main_table.'.category_id', 'pct.parent_id')
                ->where(function ($query) use ($local) {
                    $query->where('pct.lang', $local)
                    ->orWhere('pct.lang', '=', null);
                })
                ->where(self::$translates_table.'.lang', $local)
                ->select(
                    self::$main_table.'.*',
                    self::$translates_table.'.title',
                    self::$translates_table.'.alt',
                    'pct.title AS cat_title'
                )
                ->when($status_on, function ($query, $status_on) {
                    return $query->where(self::$main_table.'.status', $status_on);
                })
                ->when($where_in, function ($query, $where_in) {
                    return $query->whereIn(self::$main_table.'.id', $where_in);
                })
                ->when($where_in_cat, function ($query, $where_in_cat) {
                    return $query->whereIn(self::$main_table.'.category_id', $where_in_cat);
                })
                ->orderBy('id', 'DESC')
                ->when($get, function ($query, $get) {
                    return $query->get();
                })
                ->when($paginate, function ($query, $paginate) {
                    return $query->paginate(9);
                });
    }
}
