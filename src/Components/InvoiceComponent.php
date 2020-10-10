<?php 
namespace Component\Arminpay\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component as BaseComponent;
use Component\Arminpay\Order;
use Core\HttpSite\Contracts\Resourceable;
use Core\HttpSite\Concerns\IntractsWithResource;
use Core\HttpSite\Concerns\IntractsWithLayout;
use Core\Document\Document;

class InvoiceComponent extends BaseComponent implements Resourceable
{       
	use IntractsWithResource, IntractsWithLayout;
/**
	 * Name of Component.
	 * 
	 * @var null
	 */
	protected $name = 'invoice';

	/**
	 * Label of Component.
	 * 
	 * @var null
	 */
	protected $label = 'arminpay::title.invoices';

	/**
	 * SingularLabe of Component.
	 * 
	 * @var null
	 */
	protected $singularLabel = 'arminpay::title.invoice'; 

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'order/{tracking_code}/invoice';

	/**
	 * Route Conditions of section
	 * 
	 * @var null
	 */
	protected $wheres = [ 
		'tracking_code'	=> '[0-9]+'
	];   

	public function toHtml(Request $request, Document $docuemnt) : string
	{       
		$order = Order::where([
			'tracking_code' => $request->route('tracking_code'),
			'status' => ['!=' => Order::SUCCESS]
		])->firstOrFail();
 		
 		dd($order);

		$this->resource($order);  

		$docuemnt->title($order->metaTitle()?: $order->title); 
		
		$docuemnt->description($order->metaDescription()?: $order->intro_text);   

		return $this->firstLayout($docuemnt, $this->config('layout'), 'clean-order')
					->display($order->toArray(), $docuemnt->component->config('layout', [])); 
	}    
}
