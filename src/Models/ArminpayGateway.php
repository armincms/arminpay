<?php

namespace Armincms\Arminpay\Models;

use Armincms\Contract\Concerns\Configurable;
use Armincms\Contract\Concerns\InteractsWithMedia;
use Armincms\Contract\Concerns\InteractsWithWidgets;
use Armincms\Contract\Contracts\HasMedia;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class ArminpayGateway extends Model implements HasMedia
{
    use Configurable;
    use HasCheckout;
    use InteractsWithMedia;
    use InteractsWithTransactions;
    use InteractsWithWidgets;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => AsArrayObject::class,
    ];

    /**
     * Query that where enabled.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnable($query)
    {
        return $this->whereEnabled(1);
    }

    /**
     * Get the value that should be displayed to represent the model.
     *
     * @return string
     */
    public function title(): string
    {
        return (string) data_get($this, 'name.'.app()->getLocale()) ?? array_shift((array) $this->name);
    }

    /**
     * Serialize the model to pass into the client view.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForWidget($request, $detail = true): array
    {
        $driver = $this->createDriver();

        return array_merge([
            'name' => $this->title(),
            'id' => $this->getKey(),
            'driver' => $this->driver,
            'meta' => method_exists($driver, 'serializeForWidget') ? $driver->serializeForWidget($request) : [],
        ], $this->getFirstMediasWithConversions()->toArray());
    }
}
