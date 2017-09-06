<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // permissions
    protected $fillable = ['name', 'description'];

    // Product has many Prices relationship
    public function prices() 
    {
    	return $this->hasMany('App\Price');
    }

     public function activePrice() 
    {
    	return $this->prices()->where('active', '=', 1);
    }
}
