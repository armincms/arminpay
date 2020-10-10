<?php 
namespace Component\Arminpay;

use Illuminate\Support\Collection;


class ProductCollection extends Collection
{ 
	public function totalPrice()
	{
		return (float) $this->sum('price');
	}

	public function currency()
	{
		return $this->max('unit');
	}
}
