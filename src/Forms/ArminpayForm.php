<?php 
namespace Armincms\Arminpay\Forms; 

use Core\Crud\Forms\MultilingualResourceForm;
use Core\Crud\Concerns\HasImage;  
use Core\Crud\Concerns\HasPublishing; 
use Core\Crud\Concerns\HasSeo; 
use Core\Crud\Contracts\TabForm; 
use Core\Crud\Concerns\HasPermalink;
use Core\Crud\Concerns\HasTextEditor;
use Core\User\Concerns\HasOwnable;

class ArminpayForm extends MultilingualResourceForm implements TabForm
{ 
	use HasImage, HasPublishing, HasSeo, HasTextEditor, HasOwnable, HasPermalink;

	protected $uploadPath = 'arminpays';

	protected $name = 'arminpay';

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
		return imager_schema('arminpay');
	}
 

}
