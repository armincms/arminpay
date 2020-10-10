<?php 
namespace Armincms\Arminpay\Http\Controllers\Web;

use Armin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Armincms\Arminpay\Order;
use Armincms\Arminpay\Transaction;


class VerifyController extends Controller
{ 
	/**
	 * Store new product into database.
	 * 
	 * @param  Request $request 
	 * @return \Illuminate\Http\Response           
	 */
    public function __invoke(Request $request, $trackingCode)
    {
    	$transaction = Transaction::where('tracking_code', $trackingCode)
                                        ->firstOrFail()->finish($request);

    	//back into payment form
    	$request->headers->set('referer', $transaction->previous_url);  

    	$this->validate(
            $request->merge(['gateway' => $transaction->gateway]), $this->rules($transaction)
        );  

        $this->putSessionTrace($trackingCode);

    	return redirect()->to($transaction->callback_url); 
    }   

    public function rules(Transaction $transaction)
    {
        return [
            'gateway' => function($attribute, $value, $fail) use ($transaction) {
                $transaction->verified() || $fail(implode('<br>', $transaction->gateway()->errors()));
            }
        ];
    }

    public function putSessionTrace(string $trackingCode)
    { 
        session(['arminpay.trace' => $trackingCode]);
    }
}