<?php

namespace Armincms\Arminpay\Models;

use Illuminate\Http\Request; 
use Armincms\Arminpay\Contracts\Billable;

trait InteractsWithTransactions
{
    /**
     * Query the related Transactions
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function transactions()
    {
        return $this->hasMany(ArminpayTransaction::class, 'gateway_id');
    }

    /**
     * Insert new Transaction to storage.
     * 
     * @param  \Illuminate\Http\Request  $request  
     * @param  \Armincms\Arminpay\Contracts\Billable $billable 
     * @return \Armincms\Arminpay\Models\Transaction        
     */
    public function createBilling(Request $request, Billable $billable)
    {  
        return tap($this->createBillingInstance($request, $billable), function($billing) use ($billable) { 
            $billing->gateway()->associate($this);
            $billing->billable()->associate($billable);

            $billing->save();
        }); 
    }  

    /**
     * Initialize new Transaction model.
     * 
     * @param  \Illuminate\Http\Request  $request  
     * @param  \Armincms\Arminpay\Contracts\\Billable $billable 
     * @return \Armincms\Arminpay\Models\Transaction            
     */
    public function createBillingInstance(Request $request, Billable $billable)
    {
        return $this->transactions()->getModel()->forceFill([
            'amount' => $billable->amount(),
            'currency' => $billable->currency(),
            'callback_url' => $billable->callback(), 
            'reference_number' => null, 
            'payload' => (array) $request->get('payload'),
        ]);
    }
}
