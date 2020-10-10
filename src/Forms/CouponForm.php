<?php 
namespace ComponentArminpay\Forms; 

use Core\Crud\Forms\ResourceForm;
use Core\Crud\Concerns\HasImage;  
use Core\Crud\Concerns\HasPublishing; 
use Core\Crud\Concerns\HasSeo; 
use Core\Crud\Contracts\TabForm; 
use Core\Crud\Concerns\HasPermalink;
use Core\Crud\Concerns\HasTextEditor;
use Core\User\Concerns\HasOwnable;

class CouponForm extends ResourceForm implements TabForm
{ 
	use HasImage, HasPublishing, HasSeo, HasTextEditor, HasOwnable, HasPermalink;

	protected $uploadPath = 'coupons';

	protected $name = 'coupon';

	public function build()
	{         
		$this->field('text', 'title', true, 'armin::title.title');
	}     
 

	public function generalMap()
	{ 
		return [];
	}
	public function translateMap()
	{
		return [];
	}
	public function relationMap()
	{
		return [];
	}  

	public function schemas($name)
	{
		return imager_schema('coupon');
	}
 

}
