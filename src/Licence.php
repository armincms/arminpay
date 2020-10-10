<?php 
namespace Component\Arminpay;

use Component\Arminpay\Contracts\Orderable;
use Component\Arminpay\Contracts\Product;
use Component\Arminpay\Product as Model; 

class Licence implements Orderable
{ 
	public function name(): string
	{
		return 'licence-product';
	}

	public function resolve($orderable) : Product
	{
		return new Model([
			'price' => 132 * $orderable,
			'discount' => 22 * $orderable,
			'unit' => 'IRR',
			'title' => "Product {$orderable} Example.",
		]);
	}
}
