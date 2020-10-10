<?php 
namespace ComponentArminpay\Http\Controllers;

use Armin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\Crud\Resource;
use ComponentArminpay\Coupon;
use ComponentArminpay\Forms\CouponForm;
use ComponentArminpay\Tables\CouponTransformer;
use Core\Crud\Contracts\PublicatableResource;

class CouponController extends Resource implements PublicatableResource
{   
    public function name()
    {
        return 'coupon';
    }
    public function title()
    {
        return 'coupon::title.content';
    } 
    public function columns()
    {
        return [
            'id' => [
                'title'      => armin_trans('armin::title.id'),
                'searchable' => true
            ], 
        ];
    }  
    public function model()
    {
        return new Coupon;
    } 
    public function form()
    {
        return new CouponForm;
    } 

    protected function getTableTransformer()
    {
        return new CouponTransformer($this);
    }


    public function getAvailableStatuses()
    {
        return ['draft', 'pending', 'published', 'scheduled'];
    }  
    public function getStatusColumn()
    {
        return 'status';
    }  
}
