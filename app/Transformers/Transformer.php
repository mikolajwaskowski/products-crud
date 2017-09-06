<?php

namespace App\Transformers;

abstract class Transformer {

	public function transormCollection(array $items) 
	{
		return array_map([$this, 'transform'], $items);
	}


	public abstract function transform($item);
}