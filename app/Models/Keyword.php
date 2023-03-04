<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $table = 'searched_keywords';

    public static function allItems()
    {
        return Keyword::orderBy('id', 'DESC')->paginate(10);
    }
}
