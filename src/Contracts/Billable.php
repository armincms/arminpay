<?php

namespace Armincms\Arminpay\Contracts;

interface Billable
{
    /**
     * The payment amount.
     *
     * @return float
     */
    public function billingAmount(): float;

    /**
     * The payment currency.
     *
     * @return float
     */
    public function billingCurrency(): string;

    /**
     * Return the path that should be called after the success payment.
     *
     * @return float
     */
    public function successCallback(): string;

    /**
     * Return the path that should be called after the failed payment.
     *
     * @return float
     */
    public function failCallback(): string;
}
