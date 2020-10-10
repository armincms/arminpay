<?php 
namespace Component\Arminpay\Tables;

use Core\Crud\Tables\ResourceTransformer; 

class OrderTransformer extends ResourceTransformer
{   
    /**
     * @param \Illuminate\Database\Eloquent\Model $resource
     * @return array
     */ 
    public function toArray($resource)
    { 
        return array_merge(['ip' => data_get($resource, 'extra.ip', '-')], parent::toArray($resource));
    }
}
