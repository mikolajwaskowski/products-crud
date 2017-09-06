<?php

namespace App\Transformers;

class ProductsTransformer extends Transformer 
{

	public function transform($item)
    {
        $name = $item['name'];
        $description = $item['description'];

        if($item['active_price']) {
        	$active_price = $item['active_price'][0]['amount'];
        } else {
        	$active_price = -1;
        }

        return [
        	'id' => $item['id'],
            'name' => $item['name'],
            'description' => $item['description'],
            'created_at' => $item['created_at'],
            'updated_at' => $item['updated_at'],
            'active_price' => $active_price,
            'prices' => $item['prices']
        ];
    }
}