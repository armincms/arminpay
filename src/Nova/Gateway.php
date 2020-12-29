<?php

namespace Armincms\Arminpay\Nova;

use Illuminate\Support\Str; 
use Illuminate\Http\Request; 
use Laravel\Nova\Fields\{ID, Text, Select, Boolean};
use Armincms\Fields\{InteractsWithJsonTranslator, Targomaan, Chain};  
use MediaLibrary;

class Gateway extends Resource
{
    use InteractsWithJsonTranslator;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Arminpay\Models\ArminpayGateway::class; 

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = [ 
    ];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];


    /**
     * The JSON columns that should be searched.
     *
     * @var array
     */
    public static $searchJson = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),   

            Chain::as('gateway', function() {
                return [
                    Select::make(__('Driver'), 'driver')
                        ->options($this->drivers())
                        ->required()
                        ->rules('required'),
                ];
            }),

            Targomaan::make([
                Text::make(__('Name'), 'name')
                    ->required()
                    ->rules('required')   
            ]),   

            Chain::with('gateway', function($request) {
                return $this->filter([
                    new Fields\DriverFields($request, $request->get('driver', $this->driver))
                ]);
            }),   

            $this->when($request->resourceId && ! $request->editing, function() use ($request) {
                return new Fields\DriverFields($request, $this->driver);
            }),

            Boolean::make(__('Enabled'), 'enabled'),

            $this->imageField('Logo', 'logo')
                    ->conversionOnPreview('common-thumbnail') 
                    ->conversionOnDetailView('common-thumbnail') 
                    ->conversionOnIndexView('common-thumbnail'),
        ];
    }   

    /**
     * Returns array of gateway drivers.
     * 
     * @return array
     */
    public function drivers()
    {
        return collect(app('arminpay')->availableDrivers())->flip()->map(function($label, $driver) {
            return __(Str::title($driver));
        });
    } 
}
