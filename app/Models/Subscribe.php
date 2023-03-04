<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    public static function allItems()
    {
        return Subscribe::orderBy('id', 'DESC')->paginate(10);
    }
}
