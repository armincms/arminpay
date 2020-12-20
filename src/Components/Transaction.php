<?php 
namespace Armincms\Arminpay\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component; 
use Core\HttpSite\Contracts\Resourceable;
use Core\HttpSite\Concerns\{IntractsWithResource, IntractsWithLayout}; 
use Core\Document\Document; 
use Armincms\Arminpay\Models\{Transaction as TransactionModel, Gateway};
use Armincms\Arminpay\{Helper, Transaction as Builder};

class Transaction extends Component implements Resourceable
{       
	use IntractsWithResource, IntractsWithLayout; 

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'transaction';

	public function toHtml(Request $request, Document $docuemnt) : string
	{         
		$this->validate([
			'billableName' 	=> 'required|string',
			'billableId' 	=> 'required|integer',
			'gateway' 		=> 'required|integer',
		]);

		$billable = forward_static_call([$request->billableName, 'findOrFail'], $request->billableId);
		$gateway = Gateway::findOrFail($request->gateway);

		if(Helper::isBillable($billable)) {
			return Builder::for($billable)->via($gateway)->redirect();
		} 

		throw new \Exception("Invalid Billable", 500);		
	} 

	public function method()
	{
	 	return 'post';
	}  
}
