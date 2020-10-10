<?php 
namespace Component\Arminpay\Http\Controllers\Web;

use Armin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Component\Arminpay\Order;


class OrderCreateController extends Controller
{ 
	/**
	 * Store new product into database.
	 * 
	 * @param  Request $request 
	 * @return \Illuminate\Http\Response           
	 */
    public function __invoke(Request $request)
    {
    	$this->validate($request, $this->rules());

        $order = $this->createOrder($request);

    	return ['tracking_code' => $order->trackingCode()]; 
    }

    /**
     * Make new Order instance;
     * 
     * @param  \Illuminate\Http\Request $request 
     * @return \Illuminate\Database\ELoquent\Model           
     */
    public function createOrder(Request $request)
    {  
        $data = $this->fetchDataFromRequest($request); 

        return tap(Order::create($data), function($order) use ($request) {
            if($user = $request->user()) {
                $order->owner()->associate($user)->save();
            } else if($id = $request->customer_id) {
                $order->update([ 
                    'customer_id'   => $id,
                    'customer_type' => $request->customer_user ?: config('auth.providers.users.model'),
                ]);
            }
        });
    }

    /**
     * Fetch required order data from request.
     * 
     * @param  \Illuminate\Http\Request $request 
     * @return array           
     */
    public function fetchDataFromRequest(Request $request)
    {
        return [
            'order_detail'  => $this->resolveProducts($request->orderable, (array) $request->products), 
            'coupon'        => $request->coupon,
            'extra' => array_merge(['ip' => $request->ip()], (array) $request->get('extra', [])),
        ];
    }

    /**
     * Resolve ordered products.
     * 
     * @param  string $orderable 
     * @param  array  $products  
     * @return array            
     */
    public function resolveProducts(string $orderable, array $products = []) 
    {
        $orderable = $this->resolveOrderable($orderable);

        return collect($products)->map(function($product) use ($orderable) {
            return $orderable->resolve($product)->jsonSerialize();
        })->toArray();
    }

    /**
     * Resolve orderable instance.
     * 
     * @param  string $name 
     * @return \Component\Arminpay\Contracts\Orderable       
     */
    public function resolveOrderable(string $name)
    {
        return app('arminpay.order')->resolve($name);
    }

    /**
     * Order data validation rules.
     * 
     * @return array
     */
    public function rules()
    {
    	return [
            'products'      => 'required',
            'customer_id'   => 'integer',  
            'orderable' => [
                'required', 
                function($attribute, $value, $fail) {
                    $this->hasOrderable($value) || $fail('invalid orderable'); 
                }
            ], 
    	];
    }

    /**
     * Check orderable existence.
     * 
     * @param  string  $orderable 
     * @return boolean            
     */
    public function hasOrderable(string $orderable)
    {
        try {
            app('arminpay.order')->resolve($orderable);
        } catch (\Exception $e) {return false;} 

        return true;
    }
}