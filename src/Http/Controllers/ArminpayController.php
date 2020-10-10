<?php 
namespace Armincms\Arminpay\Http\Controllers;

use Armin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\Crud\MultilingualResource;
use Armincms\Arminpay\Arminpay;
use Armincms\Arminpay\Forms\ArminpayForm;
use Armincms\Arminpay\Tables\ArminpayTransformer;
use Core\Crud\Contracts\PublicatableResource;

class ArminpayController extends MultilingualResource implements PublicatableResource
{   
    public function name()
    {
        return 'arminpay';
    }
    public function title()
    {
        return 'arminpay::title.content';
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
        return new Arminpay;
    } 
    public function form()
    {
        return new ArminpayForm;
    } 

    protected function getTableTransformer()
    {
        return new ArminpayTransformer($this,  language());
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
