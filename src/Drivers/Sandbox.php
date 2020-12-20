<?php

namespace Armincms\Arminpay\Drivers;

use Illuminate\Http\Request;
use Laravel\Nova\fields\Text;
use Armincms\Arminpay\Contracts\{Gateway, Billing}; 

class Sandbox implements Gateway
{ 
	/**
	 * Make payment for the given Billing.
	 * 
	 * @param  \Illuminate\Http\Request  $request  
	 * @param  \Armincms\Arminpay\Contracts\Billing $billing  
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
     * @throws \InvalidArgumentException
	 */
	public function pay(Request $request, Billing $billing)
	{  
		return redirect($billing->callback());
	} 

	/**
	 * Verify the payment for the given Billing.
	 * 
	 * @param  \Illuminate\Http\Request  $request  
	 * @param  \Armincms\Arminpay\Contracts\Billing $billing  
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
     * @throws \InvalidArgumentException
	 */
	public function verify(Request $request, Billing $billing)
	{
		return time();

	} 
 
	/**
	 * Returns configuration fields.
	 * 
	 * @return array 
	 */
	public function fields(Request $request): array
	{
		return [
			Text::make('Merchant ID')
				->help(__('This is a test gateway. do not use it for commercial payments.'))
				->required()
				->rules('required'),
		];
	} 
}