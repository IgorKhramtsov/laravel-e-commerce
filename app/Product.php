<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ["name", "price", "description", "image_url"];

    public function reviews()
    {
        return $this->hasMany('App\Review')->where('is_published', '=', '1');
    }
    public function order_items()
    {
        return $this->hasMany("App\OrderItems");
    }
}
