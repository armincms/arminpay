<?php 
namespace Component\Arminpay\Tables;

use Core\Crud\Tables\MultilingualResourceTransformer; 

class GatewayTransformer extends MultilingualResourceTransformer
{   
    /**
     * @param \Illuminate\Database\Eloquent\Model $resource
     * @return array
     */ 
    public function toArray($resource)
    {
        return parent::toArray($resource);
    }
}
