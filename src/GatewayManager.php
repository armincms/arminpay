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
     * Call a custom driver creator.
     *
     * @param  string  $driver
     * @return mixed
     */
    protected function callCustomCreator($driver)
    {
        return $this->customCreators[$driver]($this->container, $this->getDriverConfiguration($driver));
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

    /**
     * Return`s the driver configurations.
     * 
     * @param string $driver 
     * @param array 
     */
    public function getDriverConfiguration($driver): array
    {
        return (array) $this->config->get("arminpay.{$driver}", []);
    }

    /**
     * Set the driver configurations.
     * 
     * @param string $driver 
     * @param $this
     */
    public function setDriverConfiguration(string $driver, array $config)
    {
        $this->config->set("arminpay.{$driver}", $config);

        return $this;
    }

    /**
     * Merge the given configurations.
     * 
     * @param  array  $config 
     * @return $this         
     */
    public function mergeConfigurations(array $config)
    {
        $this->config->set('arminpay', array_merge((array) $this->config->get('arminpay'), $config));

        return $this;
    }
}