<?php 
namespace Component\Arminpay\Contracts;


interface Order extends Trackable
{
	public function totalPrice(): float;   
	public function unitPrice(): string;   
}