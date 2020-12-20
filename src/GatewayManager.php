<?php

namespace Armincms\Arminpay;
 
use InvalidArgumentException;
use Armincms\Arminpay\Contracts\Gateway; 
use Illuminate\Support\Manager;


class GatewayManager extends Manager
{  
    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
    	return tap(parent::createDriver($driver), function($driver) {
    		if(! $driver instanceof Gateway){
    		 	throw new InvalidArgumentException(
    		 		"Driver [$driver] do not implements `Armincms\Arminpay\Contracts\Gateway`."
    		 	);
    		}
    	});  
    } 

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
    	return 'sandbox';
    }

    /**
     * Create sandbox driver for test payment.
     * 
     * @return
     */
    public function createSandboxDriver()
    {
        return new Drivers\Sandbox;
    }

    /**
     * Get all of the registered "drivers".
     *
     * @return array
     */
    public function availableDrivers()
    {
        return array_merge(['sandbox'], array_keys($this->customCreators));
    }

    /**
     * Determine if the given driver exists.
     * 
     * @param  string  $driver 
     * @return boolean         
     */
    public function has(string $driver = null)
    {
        return in_array($driver, $this->availableDrivers());
    }
}