<?php 

namespace Armincms\Arminpay\Contracts;
 

interface Billing extends Billable 
{ 
	/**
	 * The payment payload.
	 * 
	 * @return float
	 */
	public function payload(): array; 

	/**
	 * Get the billing identifier.
	 * 
	 * @return string
	 */
	public function getIdentifier();
}