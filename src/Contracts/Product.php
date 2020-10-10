<?php 
namespace Armincms\Arminpay\Contracts;

use JsonSerializable;
 
interface Product extends JsonSerializable
{
	/**
	 * Title of the sold product.
	 * 
	 * @param  string $title 
	 * @return $this       
	 */
	public function title(string $title);   

	/**
	 * Description of the sold product.
	 * 
	 * @param  string $description 
	 * @return $this       
	 */
	public function description(string $description = null);  

	/**
	 * Price of the sold product.
	 * 
	 * @param  float $price 
	 * @return $this       
	 */
	public function price(float $price);   

	/**
	 * Unit-Price of the sold product.
	 * 
	 * @param  string $unit 
	 * @return $this       
	 */
	public function unit(string $unit = 'IRR');   

	/**
	 * Discounted  of the sold product.
	 * 
	 * @param  float $discount 
	 * @return $this       
	 */
	public function discount(float $discount = 0.00); 

	/**
	 * Additional Data.
	 * 
	 * @param  array $meta 
	 * @return $this       
	 */  
	public function withMeta(array $meta);  
}