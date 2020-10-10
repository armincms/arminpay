<?php
namespace Armincms\Arminpay;

use Armincms\Arminpay\Contracts\Manager;
use Armincms\Arminpay\Contracts\Gateway; 


class GatewayManager implements Manager
{
	protected $gateways = [];

	public function resolve(string $name)
	{  
		abort_unless($this->has($name), 422, "Invalid Gateway {$name}"); 

		return $this->gateways[$name];
	} 

	public function register(Gateway $gateway)
	{
		$this->gateways[$gateway->name()] = $gateway;

		return $this;
	} 

	public function has(string $name)
	{
		return isset($this->gateways[$name]);
	}

	public function all()
	{
		return $this->gateways;
	}
}