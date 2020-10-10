<?php 
namespace Component\Arminpay\Contracts;

interface Orderable
{
	public function name(): string;
	public function resolve($orderable) : Product;
}