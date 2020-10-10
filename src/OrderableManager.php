<?php
namespace Armincms\Arminpay;

use Armincms\Arminpay\Contracts\Manager;
use Armincms\Arminpay\Contracts\Orderable;
use Illuminate\Support\Collection;


class OrderableManager implements Manager
{
	protected $orderables = [];

	public function resolve(string $name)
	{ 
		abort_unless(isset($this->orderables[$name]), 422, "Invalid Orderable {$name}");

		return $this->orderables[$name];
	} 

	public function register(Orderable $orderable)
	{
		$this->orderables[$orderable->name()] = $orderable;

		return $this;
	} 
}