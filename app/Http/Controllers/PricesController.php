<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Price;

class PricesController extends ApiController
{
    // API  - get the all existing prices
    public function getAll(Request $request)
    {
    	$prices = Price::get();
        if(count($prices) == 0) {
            return 'EMPTY';
        }
    	
        return $prices;
    }

    // API - store a price
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'amount' => 'required'
        ]);
        
        return Price::create([ 
        	'product_id' => request('product_id'),
			'amount' => request('amount')
         ]);
    }

    // API - delete price
    public function destroy($id)
    {
        $price = Price::findOrFail($id);
        $price->delete();

        return 204;
    }
}
