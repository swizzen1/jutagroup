<?php

namespace App\Models;

use App\Traits\ActionLog;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Seo extends Model
{
    use ActionLog;

    protected $fillable = ['image'];

    private static $current_class = __CLASS__;

    private static $translates_class = 'App\Models\SeosTranslate';

    private static $main_table = 'seos';

    private static $translates_table = 'seos_translates';

    public static function get_required_lang()
    {
        return DB::table('configurations')->select('admin_lang')->first()->admin_lang;
    }

    public static function addItem($request)
    {
        $class_base_name = class_basename(self::$current_class);
        $item = new self::$current_class;
        $table_columns = Schema::getColumnListing(self::$main_table);
        $request_keys = $request->except(['_token', 'translates']);

        foreach ($request_keys as $key => $value) {
            if (in_array($key, $table_columns)) {
                $item->$key = $value;
            }
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
            $item::storeLog($item, __CLASS__, 'Create');

            return true;
        }

        return false;
    }

    public static function updateItem($request, $item)
    {
        $class_base_name = class_basename(self::$current_class);
        $table_columns = Schema::getColumnListing(self::$main_table);
        $request_keys = $request->except(['_token', 'translates']);

        foreach ($request_keys as $key => $value) {
            if (in_array($key, $table_columns)) {
                $item->$key = $value;
            }
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
            $item::storeLog($item, __CLASS__, 'Update');

            return true;
        }

        return false;
    }

    public static function getItemInfo($route = '', $local = '')
    {
        if (property_exists(__CLASS__, 'translates_table')) {
            return self::$current_class::join(self::$translates_table, self::$main_table.'.id', '=', self::$translates_table.'.parent_id')
                ->where(self::$main_table.'.route', $route)
                ->where(self::$translates_table.'.lang', $local)
                ->select(self::$main_table.'.*',
                    self::$translates_table.'.page_title',
                    self::$translates_table.'.meta_title',
                    self::$translates_table.'.meta_description'
                )
                ->first();
        } else {
            return self::$current_class::where(self::$main_table.'.id', $id)->first();
        }
    }

    public static function allItems($local = '')
    {
        if (property_exists(__CLASS__, 'translates_table')) {
            return self::$current_class::join(self::$translates_table, self::$main_table.'.id', '=', self::$translates_table.'.parent_id')
                    ->where(self::$translates_table.'.lang', $local)
                    ->select(self::$main_table.'.*',
                        self::$translates_table.'.page_title',
                        self::$translates_table.'.meta_title',
                        self::$translates_table.'.meta_description'
                    )
                    ->orderBy('id', 'DESC')
                    ->get();
        } else {
            return self::$current_class::select('*')->orderBy('id', 'DESC')->get();
        }
    }
}
