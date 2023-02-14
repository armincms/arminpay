<?php

namespace Armincms\Arminpay\Nova\Actions;

use Armincms\Arminpay\Nova\Gateway;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class CreateGateway extends Action
{
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $gateway = tap(Gateway::newModel(), fn ($gateway) => $gateway->forceFill(['driver' => $fields->get('driver'), 'name' => []])->save());

        return static::visit('resources/'.Gateway::uriKey()."/{$gateway->getKey()}/edit");
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make(__('Gateway Driver'), 'driver')
                ->options(Gateway::drivers())
                ->required()
                ->rules('required'),
        ];
    }
}
