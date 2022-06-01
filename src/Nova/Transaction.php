<?php

namespace Armincms\Arminpay\Nova;

use Illuminate\Support\Str; 
use Illuminate\Http\Request; 
use Laravel\Nova\Fields\{Code, Badge, Text, Number, DateTime, BelongsTo, MorphTo};   
use Armincms\Nova\Fields\Money;
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
        'reference_number', 'amount', 'tracking_code'
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
            Text::make(__('Tracking Code'), 'tracking_code')->sortable(),

            Text::make(__('Bank Reference'), 'reference_number')
                ->sortable(),

            $this->currencyField(__('Amount'), 'amount'),

            MorphTo::make(__('Billlable'), 'billable')
                ->types(Helper::billableResources($request)->all()),

            BelongsTo::make(__('Gateway'), 'gateway', Gateway::class)
                ->sortable(),

            Badge::make(__('Status'), 'marked_as') 
                ->map([
                    $this->getDraftValue()  => 'info',
                    $this->getPendingValue()=> 'info',
                    $this->getSuccessValue()=> 'success',
                    $this->getFailsValue()  => 'danger', 
                    $this->getCancellationValue() => 'warning',
                    
                ]),

            DateTime::make(__('Payment Date'), 'created_at')
                ->sortable(),

            $this->when(! is_null($this->exception), function() {
                return Code::make(__('Exception'), 'exception')->onlyOnDetail();
            }),
        ];
    }   

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizeToCreate(Request $request)
    { 
        return false;
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
    public function authorizedToUpdate(Request $request)
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
