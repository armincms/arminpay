<?php

namespace Armincms\Arminpay\Contracts;

interface Billing
{
    /**
     * The payment amount.
     *
     * @return float
     */
    public function amount(): float;

    /**
     * The payment currency.
     *
     * @return float
     */
    public function currency(): string;

    /**
     * Return the path that should be called after the payment.
     *
     * @return float
     */
    public function callback(): string;

    /**
     * The payment payload.
     *
     * @return float
     */
    public function payload(): array;

    /**
     * Get the billing identifier.
     *
     * @return string
     */
    public function getIdentifier();
}
