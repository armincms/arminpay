<?php 
namespace Component\Arminpay; 

use Component\Arminpay\Contracts\Gateway;  
use Component\Arminpay\Contracts\Tradable;  
use Illuminate\Support\Str;

abstract class Gate implements Gateway
{
	/**
	 * Gateway configurations.
	 * 
	 * @var array
	 */
	protected $config = [];

	/**
	 * PAyment amount.
	 * 
	 * @var \Component\Arminpay\Contracs\Tradable
	 */
	protected $transaction; 

	/**
	 * Gateway configuration errors.
	 * 
	 * @var array
	 */
	protected $errors = [];


	public function __construct(array $config = [])
	{
		$this->config = $config;
	}

	public function name(): string
	{
		return Str::lower(class_basename($this));
	}  	 	

	public function prepare(Tradable $transaction): Gateway
	{
		$this->transaction = $transaction; 

		return $this; 
	}    

	public function label(): string
	{ 
		return $this->config('label') ?: Str::title($this->name());
	}  	

	public function logo(): ?string
	{
		return $this->config('logo');
	} 

	public function isActive()
	{
		return (boolean) $this->config('active', false);
	}

	public function config(string $key = null, $default = null)
	{ 
		return is_null($key) ? $this->config : array_get($this->config, $key, $default);
	}

	public function withConfig(array $config)
	{
		$this->config = array_merge($this->config, $config);

		return $this;
	}

	public function ready()
	{
		return count($this->errors()) === 0;
	}

	public function errors()
	{
		return (array) $this->errors;
	}

	abstract public function fields(): array;
}
