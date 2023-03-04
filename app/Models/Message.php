<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['name', 'email', 'website', 'message'];

    public static function allItems()
    {
        return Message::orderBy('id', 'DESC')->paginate(10);
    }
}
