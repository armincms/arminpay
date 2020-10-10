<?php  
namespace Armincms\Arminpay;

use Core\Language\Translate;

class GatewayTranslation extends Translate
{  
	
    protected $guarded = [];
    protected $casts = [   
    	'seo'    => 'collection',
    ];  
}
