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
            return $this->respondWithError('Nie znaleziono żadnych produktów.');
        }
        return $this->respond(
        	$this->productsTransformer->transormCollection($products->toArray())
		);
    }

    // API  - get the all existing products
    public function getSpecified(Product $product)
    {
    	if (!$product) {
    		return $this->respondWithError('Nie znaleziono danego produktu.');
    	}
        return $this->respond(
        	$this->productsTransformer->transform($product)
		);
    }

    // API - store a product
    public function store(Request $request)
    {
    	$prodValidator = \Validator::make(
    		$request->all(), [
    			'name' => 'required', 
    			'description' => 'required',
    			'prices.*.amount' => 'required',
    			'prices.*.active' => 'required'
    		]
    	);
    	if( $prodValidator->fails() ) {
    		return $this->respondWithError('Dane nie przeszły walidacji...');
    	}
        
        $product = Product::create([ 
			'name' => request('name'),
        	'description' => request('description'),
         ]);

        foreach ($request['prices'] as $price) {
        	Price::create([
        		'product_id' => $product->id,
        		'amount' => $price['amount'],
        		'active' => $price['active']
        	]);
        }

        return $this->respondCreated();
    }


    // API - update a product
    public function update(Request $request) 
    {
    	$prodValidator = \Validator::make(
    		$request->all(), [
    			'name' => 'required', 
    			'description' => 'required',
    			'prices.*.amount' => 'required',
    			'prices.*.active' => 'required'
    		]
    	);
    	if( $prodValidator->fails() ) {
    		return $this->respondWithError('Dane nie przeszły walidacji...');
    	}

    	// update product data
    	$update = Product::where('id', $request->id)
          	->update([ 'name' => $request->name, 'description' => $request->description ]);
         if(!$update) {
         	return $this->respondWithError('Wystąpił błąd podczas edycji produktu.');
         }

         // find that product
        $product = Product::findOrFail($request->id);
        // delete all prices first - prevents from store unused prices for products
        // only visible Vue prices have to be stored
        $product->prices()->delete();
        foreach ($request['prices'] as $price) {
        	Price::create([
        		'product_id' => $product->id,
        		'amount' => $price['amount'],
        		'active' => $price['active']
        	]);
        }

        return $this->respondUpdated();
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
