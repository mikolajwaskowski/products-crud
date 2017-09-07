<?php

namespace App\Transformers;

class ProductsTransformer extends Transformer 
{

	public function transform($item)
    {
        $name = $item['name'];
        $description = $item['description'];

        return [
        	'id' => $item['id'],
            'name' => $item['name'],
            'description' => $item['description'],
            'created_at' => $item['created_at'],
            'updated_at' => $item['updated_at'],
            'prices' => $item['prices']
        ];
    }
}