<?php

namespace Armincms\Arminpay\Contracts;

use Illuminate\Http\Request;

interface Gateway
{
    /**
     * Make payment for the given Billing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Armincms\Arminpay\Contracts\Billing  $billing
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \InvalidArgumentException
     */
    public function pay(Request $request, Billing $billing);

    /**
     * Verify the payment for the given Billing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Armincms\Arminpay\Contracts\Billing  $billing
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \InvalidArgumentException
     */
    public function verify(Request $request, Billing $billing);

    /**
     * Returns configuration fields.
     *
     * @return array
     */
    public function fields(Request $request): array;
}
