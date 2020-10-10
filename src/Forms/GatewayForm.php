<?php 
namespace Component\Arminpay\Forms; 

use Core\Crud\Forms\MultilingualResourceForm;
use Core\Crud\Concerns\HasImage;   
use Core\Crud\Contracts\TabForm;  

class GatewayForm extends MultilingualResourceForm implements TabForm
{ 
	use HasImage;

	protected $uploadPath = 'gateways';

	protected $name = 'gateway';

	public function build()
	{         
		if($gateway = $this->getModel()) {
			$this
				->field('select', 'status', false, 'armin::title.status', [
					'activated' 	=> armin_trans('admin-crud::status.activated'), 
					'deactivated' 	=> armin_trans('admin-crud::status.deactivated'), 
				], null, [], [], [], [
					'class' => 'block-label button-height'
				])
				->field('text', 'title', true, "armin::title.title", null, null, [], [
					'class' => 'block-label button-height'
				]); 

			collect($this->gatewayFields())->each(function($field) use ($gateway) {
				$this->field('text', "config[{$field}]", false, $field, null, $this->config($field), [], [
					'class' => 'block-label button-height'
				]);
			});

			$this->imageUploader('logo', false, [], [], false, [], [
				'class' => 'block-label button-height'
			]);
		} 
	}     
 

	public function generalMap()
	{ 
		return ['config', 'logo', 'status'];
	}
	public function translateMap()
	{
		return ['title'];
	}
	public function relationMap()
	{
		return [];
	}  

	public function schemas($name)
	{
		return imager_schema('gateway');
	}

	public function gatewayFields()
	{
		return app('arminpay.gateway')->resolve($this->getModel()->name)->fields();
	}

	public function config(string $key)
	{
		return array_get($this->getModel(), "config.{$key}");
	}
 

}
