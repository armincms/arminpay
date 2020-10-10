<?php  
namespace Component\Arminpay;

use Core\Language\Translate;

class ArminpayTranslation extends Translate
{  
	
    protected $guarded = [];
    protected $casts = [   
    	'seo'    => 'collection',
    ];  
}
