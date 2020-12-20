<?php 

namespace Armincms\Arminpay\Nova\Fields;

use Illuminate\Http\Resources\MergeValue;
use Illuminate\Http\Request;
 
class DriverFields extends MergeValue
{  
    /**
     * Create new merge value instance.
     *
     * @param  Illuminate\Http\Request $requests
     * @param  string $driver
     * @return void
     */ 
	public function __construct(Request $request, string $driver = null)
	{ 
		parent::__construct($this->prepareFields($request, $driver));
	} 

    /**
     * Returns the driver fields.
     *
     * @param  Illuminate\Http\Request $requests
     * @param  string $driver
     * @return \Illuminate\Support\Collection|array
     */ 
	public function prepareFields(Request $request, $driver)
	{
        if(app('arminpay')->has($driver)) {
            $fields = app('arminpay')->driver($driver)->fields($request);

            return collect($fields)->each(function($field) { 
                $field->fillUsing(function($request, $model, $attribute, $requestAttribute) {
                    $model->setConfig($attribute, $request->get($requestAttribute));
                });

                $field->resolveUsing(function($value, $resource, $attribute) {
                    return $resource->getConfig($attribute);
                });
            });
        }

        return [];  
	}
}