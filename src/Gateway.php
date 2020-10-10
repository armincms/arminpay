<?php  
namespace Armincms\Arminpay; 

use Illuminate\Database\Eloquent\{Model, SoftDeletes}; 
use Core\Language\Concerns\HasTranslation; 
use Core\Crud\Concerns\HasCustomImage;
use Core\Language\Contracts\Multilingual; 
use Core\Crud\Contracts\Publicatable;
use Core\Crud\Concerns\Publishing; 

class Gateway extends Model implements Multilingual
{ 
	use HasTranslation, HasCustomImage;
 
	protected $guarded = [];
	protected $casts = [
		'config' => 'collection'
	];   

    public function getLogoAttribute()
    { 
    	return $this->getImages('logo')->first();
    }  

    public function isActive()
    {
    	return $this->status == 'activated';
    }
}
