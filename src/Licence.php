<?php 
namespace Armincms\Arminpay;

use Armincms\Arminpay\Contracts\Orderable;
use Armincms\Arminpay\Contracts\Product;
use Armincms\Arminpay\Product as Model; 

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
