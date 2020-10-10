<?php 
namespace Armincms\Arminpay\Http\Controllers\Web;

use Armin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Armincms\Arminpay\Order;
use Armincms\Arminpay\Transaction;


class OrderPaymentController extends Controller
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

        $gateway = $this->prepareGateway( $this->createTransaction($request) );

        $this->validate($request, [
            'gateway' => function($attribute, $value, $fail) use ($gateway) {
                $gateway->ready() || $fail(implode('<br>', $gateway->errors()));
            }
        ]);

        $response = $gateway->redirect($request);
        $location = $response->getTargetUrl(); 
        
        return  request()->ajax() ? compact('location') : $response; 
    }  

    public function createTransaction(Request $request)
    {
        $order = $this->getOrder($request->order);

        return Transaction::create([
            'tracking_code' => $order->trackingCode(),
            'amount'        => $order->totalPrice(),
            'currency'      => $order->unitPrice(),
            'gateway'       => $request->gateway,
            'previous_url'  => url()->previous(),
            'callback_url'  => $request->callback_url,
        ]);  
    }

    public function getOrder($order)
    {
        return Order::whereTrackingCode($order)->orWhere('id', $order)->first();
    }

    public function prepareGateway($transaction)
    {
        return app('arminpay.gateway')->resolve($transaction->gateway)->prepare($transaction);
    }

    /**
     * Order data validation rules.
     * 
     * @return array
     */
    public function rules()
    {
    	return [
            'order'     => [
                'required',
                function($attribute, $order, $fail) {
                    $this->getOrder($order) || $fail("Invalid Order {$order}");
                }
            ], 
            'gateway'     => [
                'required',
                function($attribute, $gateway, $fail) {
                    app('arminpay.gateway')->has($gateway) || $fail("Invalid Gateway {$gateway}");
                }
            ], 
            'callback_url'  =>'required|url', 
    	];
    } 
}