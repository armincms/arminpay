<?php

namespace Armincms\Arminpay;

use Armincms\Arminpay\Contracts\Billable;
use Armincms\Arminpay\Models\Gateway;

class Transaction 
{
    /**
     * The request isntance.
     * 
     * @var \Illuminate\Http\Reqeust
     */
    protected static $request;

    /**
     * The billable isntance.
     * 
     * @var \Armincms\Arminpay\Contracts\Billable
     */
    protected static $billable;

    /**
     * The transaction isntance.
     * 
     * @var \Armincms\Arminpay\Models\Transaction
     */
    protected static $transaction;
 
    /**
     * The transaction isntance.
     * 
     * @param \Illuminate\Http\Reqeust
     * @param \Armincms\Arminpay\Contracts\Billable
     */
    private function __construct(Request $request, Billable $billable)
    { 
        $this->request = $request;
        $this->billable = $billable;

        $this->initialize();
    }
 
    /**
     * The transaction isntance.
     * 
     * @param \Illuminate\Http\Reqeust
     * @param \Armincms\Arminpay\Contracts\Billable
     */
    private function for(Request $request, Billable $billable)
    { 
        return new static($request, $billable);
    } 

    /**
     * Initialize Transaction isntance.
     * 
     * @return $this
     */
    public function initialize()
    { 
        $this->$transaction = tap(new Models\Transaction, function($transaction){ 
            $transaction->forceFill([
                'amount'    => $this->billable->amount(),
                'currency'  => $this->billable->currency(),
                'callback'  => $this->billable->callback(), 
            ]);

            $transaction->billable()->associate($this->billable);
        });

        return $this;
    }

    /**
     * Initialize a transaction for the given Billable resource.
     * 
     * @param  \Armincms\Arminpay\Contracts\Billable $billable 
     * @return static             
     */
    public static function for(Request $request, Billable $billable)
    {
        return new static($request, $billable);
    }

    /**
     * Initialize a transaction via the given Gateway resource.
     * 
     * @param  \Armincms\Arminpay\Models\Gateway $gateway 
     * @return static             
     */
    public function via(Gateway $gateway)
    {
        $this->transaction->gateway()->associate($gateway);

        return new static;
    }

    /**
     * Create redirect response.
     * 
     * @param  \Armincms\Arminpay\Models\Gateway $gateway 
     * @return static             
     */
    public static function redirect(Gateway $gateway)
    {
        $this->via($gateway);
        $this->transaction->save();

        $this->gateway()

        return forward_static_call([static::$transaction, 'create'])->gateway->redirect();
    }
}