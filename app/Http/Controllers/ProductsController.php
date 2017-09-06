<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Price;
use App\Transformers\ProductsTransformer;

class ProductsController extends ApiController
{
	protected $productsTransformer;

	function __construct(ProductsTransformer $productsTransformer) {
		$this->productsTransformer = $productsTransformer;
	}

	// API  - get the all existing products
    public function getAll(Request $request)
    {
    	$products = Product::with('activePrice', 'prices')->get();
        if(count($products) === 0) {
            return $this->respondWithError('Nie znaleziono żadnych produktów');
        }
        return $this->respond(
        	$this->productsTransformer->transormCollection($products->toArray())
		);
    }

     // API - store a product
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required'
        ]);
        
        $product = Product::create([ 
			'name' => request('name'),
        	'description' => request('description'),
         ]);

   //      Price::create([ 
			// 'product_id' => $product->id,
   //      	'amount' => request('price'),
   //       ]);

        return $this->respond($request);

    }

    // API - delete product
    public function destroy(Product $product)
    {
        // delete product's prices
    	$product->prices()->delete();
    	// and product
        $product->delete();

        return $this->respondDeleted();
    }
}
