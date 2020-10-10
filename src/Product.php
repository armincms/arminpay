<?php 
namespace Component\Arminpay;

use Component\Arminpay\Contracts\Product as ProductContract; 
use Illuminate\Support\Collection; 


class Product implements ProductContract
{ 
	protected $title;
	protected $description;
	protected $price;
	protected $unit;
	protected $discount;
	protected $meta = [];

	public function __construct(array $data = [])
	{ 
		Collection::make($data)->each(function ($value, $key) {
			method_exists($this, $key) ? $this->$key($value) : $this->withMeta([$key => $value]);
		});  
	}

	public function title(string $title)
	{
		$this->title = $title;

		return $this;
	} 

	public function description(string $description = null)
	{ 
		$this->description = $description;

		return $this; 
	} 

	public function price(float $price)
	{
		$this->price = floatval($price);

		return $this;
	} 

	public function unit(string $unit = 'IRR')
	{
		$this->unit = $unit;

		return $this;
	}   

	public function discount(float $discount = 0.00)
	{ 
		$this->discount = $discount;

		return $this;
	} 

	public function withMeta(array $meta)
	{
		$this->meta = array_merge((array) $this->meta, $meta);

		return $this;
	}

	public function meta()
	{
		return (array) $this->meta;
	}


    /**
     * Retrieve the value of product.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_get($this->jsonSerialize(), $key, null);
    }  

    public function __get($key)
    {
    	return $this->get($key);
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
    	return ! is_null($this->get($key));
    }

    /**
     * Prepare the product for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
    	return array_merge($this->meta(), [
    		'price'           => $this->price,
    		'discount'	      => $this->discount,
    		'unit'       	  => $this->unit,
    		'title'           => $this->title,
    		'description'     => $this->description, 
    	]);
    }  
}
