<?php 
namespace Component\Arminpay\Http\Controllers;

use Armin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\Crud\Resource;
use Component\Arminpay\Order;
use Component\Arminpay\Forms\OrderForm;
use Component\Arminpay\Tables\OrderTransformer;
use Core\Crud\Contracts\PublicatableResource;

class OrderController extends Resource implements PublicatableResource
{   
    protected $navigation = 'arminpay';
    protected $with = ['customer'];
    
    public function name()
    {
        return 'order';
    }
    public function title()
    {
        return 'arminpay::title.orders';
    } 
    public function columns()
    {
        return [
            'id' => [
                'title'      => armin_trans('armin::title.id'),
                'searchable' => true
            ], 
            'order_detail' => [
                'order_detail' => armin_trans('arminpay::title.order_detail'),
                'searchable' => true,
                'render' => 'function() {
                    if(this.order_detail == null) return "-";

                    return this.order_detail.map(function(order) {
                        return order.title + " " + order.price + " " + order.unit
                    }).join("<br>");
                }'
            ], 
            'customer' => [
                'customer' => armin_trans('arminpay::title.customer'),
                'searchable' => true, 
                'render' => 'function() {
                    return this.customer == null ? "-" : this.customer.email
                }'
            ], 
            'tracking_code' => [
                'tracking_code' => armin_trans('arminpay::title.tracking_code'),
                'searchable' => true
            ], 
            'ip' => [
                'ip' => __('IP'),
                'searchable' => false, 
                'orderable' => false,  
                'render' => 'function() {
                    if(this.extra.length == 0) return "-";

                    return this.extra.ip;
                }'
            ], 
            'created_at' => [
                'title' => __("Creation Date"),  
            ]
        ];
    }  
    public function model()
    {
        return new Order;
    } 
    public function form()
    {
        return new OrderForm;
    } 
 

    public function getAvailableStatuses()
    {
        return ['pending', 'canceled', 'failed', 'success'];
    }  
    
    public function getStatusColumn()
    {
        return 'status';
    }  
}
