<?php

namespace Armincms\Arminpay\Nova;

use Illuminate\Support\Str; 
use Illuminate\Http\Request; 
use Laravel\Nova\Fields\{ID, Text, Number, DateTime, BelongsTo, MorphTo};   
use Armincms\Arminpay\Helper;

class Transaction extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Arminpay\Models\ArminpayTransaction::class; 

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = [ 
        'billable', 'gateway'
    ];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'reference_number'
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

            Text::make(__('Reference Number'), 'reference_number')
                ->sortable(), 

            MorphTo::make(__('Billlable'), 'billable')
                ->types(Helper::billableResources($request)->all()),

            BelongsTo::make(__('Gateway'), 'gateway', Gateway::class)
                ->sortable(),

            DateTime::make(__('Payment Date'), 'created_at'),
        ];
    }   

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    { 
        return false;
    }

    /**
     * Determine if the current user can update the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToUpdate(Request $request)
    {
        return false;
    } 
}
