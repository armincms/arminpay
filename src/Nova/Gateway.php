<?php

namespace Armincms\Arminpay\Nova;

use Armincms\Fields\Targomaan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select; 

class Gateway extends Resource
{
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
    public static $with = [];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = ["id"];

    /**
     * The JSON columns that should be searched.
     *
     * @var array
     */
    public static $searchJson = ["name"];

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

            Select::make(__("Gateway Driver"), "driver")
                ->options(static::drivers())
                ->readonly()
                ->displayUsingLabels(),

            Targomaan::make([
                Text::make(__("Gateway Name"), "name")
                    ->required()
                    ->rules("required")
                    ->help(__("This name using to display to the user.")),
            ]),

            new Fields\DriverFields($request, $this->driver),

            Boolean::make(__("Enabled"), "enabled"),

            $this->medialibrary(__("Logo")),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            Actions\NewGateway::make()
                ->standalone()
                ->onlyOnIndex()
                ->canSee(function ($request) {
                    return optional($request->user())->can('create', static::newModel());
                }),
        ];
    }

    /**
     * Returns array of gateway drivers.
     *
     * @return array
     */
    public static function drivers()
    {
        return collect(app("arminpay")->availableDrivers())
            ->flip()
            ->map(function ($label, $driver) {
                return __(Str::title($driver));
            });
    }
}
