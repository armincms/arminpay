<?php 
namespace Component\Arminpay\Contracts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

interface Tradable extends Trackable 
{
	public function amount(): float; 
	public function currency(): string; 
	public function callback() : string;
	public function referenceTo(string $code) : Tradable;
}