<?php

namespace Armincms\Arminpay\Models;

use Armincms\Arminpay\Contracts\Billable;
use Illuminate\Http\Request;

trait HasCheckout
{
    /**
     * Checkout the given billing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Armincms\Arminpay\Contracts\Billable  $billing
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request, Billable $billable)
    {
        return $this->createDriver()->pay($request, $this->createBilling($request, $billable));
    }

    /**
     * Initialize related driver.
     *
     * @return \Armincms\Arminpay\Contracts\Gateway
     */
    public function createDriver()
    {
        return app('arminpay')->driver($this->driver);
    }
}
