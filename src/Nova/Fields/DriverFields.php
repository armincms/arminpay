<?php

namespace Armincms\Arminpay\Nova\Fields;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\MergeValue;
use Laravel\Nova\Fields\Boolean;

class DriverFields extends MergeValue
{
    /**
     * Create new merge value instance.
     *
     * @param  Illuminate\Http\Request  $requests
     * @param  string  $driver
     * @return void
     */
    public function __construct(Request $request, string $driver = null)
    {
        parent::__construct($this->prepareFields($request, $driver));
    }

    /**
     * Returns the driver fields.
     *
     * @param  Illuminate\Http\Request  $requests
     * @param  string  $driver
     * @return \Illuminate\Support\Collection|array
     */
    public function prepareFields(Request $request, $driver)
    {
        if (app('arminpay')->has($driver)) {
            $fields = app('arminpay')->driver($driver)->fields($request);

            return collect($fields)->each(function ($field) {
                $field->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    if ($request->exists($attribute)) {
                        $model->setAttribute(
                            "config->{$attribute}",
                            $request->get($requestAttribute)
                        );
                    }
                });

                $field->resolveUsing(function ($value, $resource, $attribute) use ($field) {
                    $value = $resource->config($attribute);

                    return $field instanceof Boolean ? boolval($value) : $value;
                });

                $field->hideFromIndex();
            });
        }

        return [];
    }
}
