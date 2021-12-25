<?php

namespace Armincms\Arminpay\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component;  
use Core\Document\Document; 
use Armincms\Arminpay\Models\ArminpayTransaction; 

class Verify extends Component 
{           

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'verify/{tracking_code}';

	/**
	 * Route Conditions of Component.
	 * 
	 * @var null
	 */
	protected $wheres = [
		'tracking_code' => '.+'
	]; 

	public function toHtml(Request $request, Document $docuemnt) : string
	{       
		$transction = ArminpayTransaction::viaCode($request->route('tracking_code'))->firstOrFail();  
			
		try {
			$transction->verify($request);
			
			return redirect($transction->callback_url);
		} catch (\Exception $e) {
			return redirect(app('site')->get('orders')->url($transction->billable->trackingCode()."/billing"))->withErrors([
				'message' => $e->getMessage(),
			]);
		}
		

		return redirect();		
	} 

	public function method()
	{
	 	return 'any';
	}  
}
