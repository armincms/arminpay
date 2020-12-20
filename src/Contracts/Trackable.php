<?php

namespace Armincms\Arminpay\Contracts;
  

interface Trackable
{
	/**
	 * Get the unique tracking code.
	 * 
	 * @return string
	 */
	public function trackingCode();  
}