<?php

namespace Armincms\Arminpay\Nova\Actions;

use Armincms\Arminpay\Nova\Gateway;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class NewGateway extends Action
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
        $gateway = tap(Gateway::newModel(), function ($gateway) use ($fields) {
            $gateway
                ->forceFill([
                    "driver" => $fields->get("driver"),
                    "name" => json_encode([
                        app()->getLocale() => $fields->get("name"),
                    ]),
                ])
                ->save();
        });

        return [
            "push" => [
                "name" => "edit",
                "params" => [
                    "resourceName" => Gateway::uriKey(),
                    "resourceId" => $gateway->getKey(),
                ],
            ],
        ];
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make(__("Gateway Driver"), "driver")
                ->options(Gateway::drivers())
                ->required()
                ->rules("required"),

            Text::make(__("Gateway Name"), "name")
                ->required()
                ->rules("required")
                ->help(__("This name using to display to the user.")),
        ];
    }
}
