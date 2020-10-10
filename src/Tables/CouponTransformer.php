<?php 
namespace ComponentArminpay\Tables;

use Core\Crud\Tables\ResourceTransformer; 

class CouponTransformer extends ResourceTransformer
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
