<?php

namespace Armincms\Arminpay\Concerns;

trait HasCancellation
{
    /**
     * Mark the model with the "cancelled" value.
     *
     * @return $this
     */
    public function asCancelled()
    {
        return $this->markAs($this->getCancellationValue());
    }

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "cancelled" value.
     *
     * @param  string  $value
     * @return bool
     */
    public function isCancelled()
    {
        return $this->markedAs($this->getCancellationValue());
    }

    /**
     * Query the model's `marked as` attribute with the "cancelled" value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelled($query)
    {
        return $query->mark($this->getCancellationValue());
    }

    /**
     * Set the value of the "marked as" attribute as "cancelled" value.
     *
     * @return $this
     */
    public function setCancellation()
    {
        return $this->setMarkedAs($this->getCancellationValue());
    }

    /**
     * Get the value of the "cancelled" mark.
     *
     * @return string
     */
    public function getCancellationValue()
    {
        return defined('static::CANCELLATION_VALUE') ? static::CANCELLATION_VALUE : 'cancelled';
    }
}
