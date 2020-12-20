<?php

namespace Armincms\Arminpay\Concerns;
 

trait HasFails 
{    
    /**
     * Mark the model with the "fails" value.
     *
     * @return $this
     */
    public function asFailed()
    {
        return $this->markAs($this->getFailsValue());
    } 

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "fails" value.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isFailed()
    {
        return $this->markedAs($this->getFailsValue());
    }

    /**
     * Query the model's `marked as` attribute with the "fails" value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeFails($query)
    {
        return $this->mark($this->getFailsValue());
    }

    /**
     * Set the value of the "marked as" attribute as "fails" value.
     * 
     * @return $this
     */
    public function setFails()
    {
        return $this->setMarkedAs($this->getFailsValue());
    }

    /**
     * Get the value of the "fails" mark.
     *
     * @return string
     */
    public function getFailsValue()
    {
        return defined('static::FAILS_VALUE') ? static::FAILS_VALUE : 'fails';
    }
}
