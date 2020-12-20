<?php 

namespace Armincms\Arminpay\Contracts;
 

interface Billable 
{ 
	/**
	 * The payment amount.
	 * 
	 * @return float
	 */
	public function amount(): float;

	/**
	 * The payment currency.
	 * 
	 * @return float
	 */
	public function currency(): string;

	/**
	 * Return the path that should be called after the payment.
	 * 
	 * @return float
	 */
	public function callback(): string;
}