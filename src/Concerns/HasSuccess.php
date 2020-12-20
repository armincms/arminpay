<?php

namespace Armincms\Arminpay\Concerns; 

trait HasSuccess 
{    
    /**
     * Mark the model with the "successed" value.
     *
     * @return $this
     */
    public function asSuccessed()
    {
        return $this->markAs($this->getSuccessValue());
    } 

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "successed" value.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isSuccessed()
    {
        return $this->markedAs($this->getSuccessValue());
    }

    /**
     * Query the model's `marked as` attribute with the "successed" value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeSuccessed($query)
    {
        return $this->mark($this->getSuccessValue());
    }

    /**
     * Set the value of the "marked as" attribute as "successed" value.
     * 
     * @return $this
     */
    public function setSuccess()
    {
        return $this->setMarkedAs($this->getSuccessValue());
    }

    /**
     * Get the value of the "successed" mark.
     *
     * @return string
     */
    public function getSuccessValue()
    {
        return defined('static::SUSCESSED_VALUE') ? static::SUSCESSED_VALUE : 'successed';
    }
}
