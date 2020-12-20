<?php

namespace Armincms\Arminpay\Concerns;
  
use Armincms\Arminpay\Models\ArminpayTransaction;

trait InteractsWithTransactions
{
    /**
     * Query the related Transactions
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function transactions()
    {
        return $this->morphMany(ArminpayTransaction::class, 'billable');
    }  
}
