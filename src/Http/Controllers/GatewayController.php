<?php 
namespace Component\Arminpay\Http\Controllers;

use Armin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\Crud\MultilingualResource;
use Component\Arminpay\Gateway;
use Component\Arminpay\Forms\GatewayForm;
use Component\Arminpay\Tables\GatewayTransformer;
use Core\Crud\Contracts\PublicatableResource;
use Core\Crud\Contracts\Compact;

class GatewayController extends MultilingualResource implements PublicatableResource, Compact
{   
    protected $navigation = 'arminpay';
    protected $publishStatus = 'activated';

    public function name()
    {
        return 'gateway';
    }
    public function title()
    {
        return 'arminpay::title.gateways';
    } 
    public function columns()
    {
        return [
            'id' => [
                'title'      => armin_trans('armin::title.id'),
                'searchable' => true
            ], 
            'title' => [
                'title'      => armin_trans('armin::title.title'),
                'searchable' => true
            ], 
        ];
    }  
    public function model()
    {
        return new Gateway;
    } 
    public function form()
    {
        return new GatewayForm;
    } 

    public function getDataTable()
    {  
        $this->syncGates();

        return parent::getDataTable();
    }

    // protected function getTableTransformer()
    // {
    //     return new GatewayTransformer($this,  language());
    // }
    public function syncGates()
    {
        $gates = Gateway::get();

        $gateways = collect(app('arminpay.gateway')->all())->filter(function($gateway) use ($gates) {
                return ! $gates->firstWhere('name', $gateway->name());
            })->map(function($gateway) {
                return [
                    'name'      => $gateway->name(), 
                    'logo'      => $gateway->logo(),
                    'created_at'=> (string) now(),
                    'updated_at'=> (string) now(),
                    'config'    => '[]',
                ];
            });

        Gateway::insert($gateways->values()->toArray());

        $translates = Gateway::get()->filter(function($gateway) {
            return $gateway->translates->count() === 0;
        })->map(function($gateway) { 
            return [
                'gateway_id'=> $gateway->id,
                'title'     => $gateway->name,
                'language'  => \App::getLocale(),
            ];
        });

        (new Gateway)->setTable('gateway_translations')->insert($translates->values()->toArray());
    }

    public function getAvailableStatuses()
    {
        return ['activated', 'deactivated'];
    }  
    public function getStatusColumn()
    {
        return 'status';
    }  
}
