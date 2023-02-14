<?php

namespace Armincms\Arminpay\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableJson;

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
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Select::make(__('Gateway Driver'), 'driver')
                ->options(static::drivers())
                ->readonly()
                ->displayUsingLabels(),

            ...collect(app('application.locales'))->flatMap(function ($locale) {
                return [
                    Text::make(__("Gateway Name - [{$locale['name']}]"), "name->{$locale['locale']}")
                        ->required()
                        ->onlyOnForms()
                        ->help($locale['name']),
                ];
            }),

            new Fields\DriverFields($request, $this->driver),

            Boolean::make(__('Enabled'), 'enabled'),

            $this->medialibrary(__('Logo')),
        ];
    }

    /**
     * Get the searchable columns for the resource.
     *
     * @return array
     */
    public static function searchableColumns()
    {
        return ['id', new SearchableJson('name')];
    }

    /**
     * Get the actions available on the entity.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            Actions\CreateGateway::make()
                ->standalone()
                ->onlyOnIndex()
                ->canSee(fn ($request) => optional($request->user())->can('create', static::newModel())),
        ];
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->resource->title();
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
     * Returns array of gateway drivers.
     *
     * @return array
     */
    public static function drivers()
    {
        return collect(app('arminpay')->availableDrivers())
            ->flip()
            ->map(fn ($label, $driver) => __(Str::title($driver)));
    }
}
