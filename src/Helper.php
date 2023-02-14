<?php

namespace Armincms\Arminpay;

use Illuminate\Http\Request;
use Laravel\Nova\Nova;

class Helper
{
    /**
     * Returns array of the transaction statuses.
     *
     * @return array
     */
    public static function statuses()
    {
        return with(new Models\ArminpayTransaction, function ($transaction) {
            return [
                $transaction->getDraftValue(),
                $transaction->getFailsValue(),
                $transaction->getPendingValue(),
                $transaction->getCancellationValue(),
                $transaction->getSuccessValue(),
            ];
        });
    }

    /**
     * Returns billing resorucse available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request [description]
     * @return \Illuminate\Support\Collection
     */
    public static function billableResources(Request $request)
    {
        return collect(Nova::availableResources($request))->filter(function ($resource) {
            return static::isBillable($resource::newModel());
        });
    }

    /**
     * Determine if the given model implements Billable.
     *
     * @param  \Illuminate\Database\Eloquent\Model
     * @return bool
     */
    public static function isBillable($model): bool
    {
        return $model instanceof Contracts\Billable;
    }
}
