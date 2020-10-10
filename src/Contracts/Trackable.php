<?php 
namespace Component\Arminpay\Contracts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

interface Trackable 
{
	public function trackingCode(); 
}