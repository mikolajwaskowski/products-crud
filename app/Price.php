<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    // permissions
    protected $fillable = ['amount', 'product_id'];

    // Price belongs to Product relationship
    public function product() 
    {
    	return $this->belongsTo('App\Product', 'product_id');
    }
}
