<?php

namespace Armincms\Arminpay\Models;

use Armincms\Arminpay\Concerns\HasCancellation;
use Armincms\Arminpay\Concerns\HasFails;
use Armincms\Arminpay\Concerns\HasSuccess;
use Armincms\Arminpay\Contracts\Billing;
use Armincms\Contract\Concerns\GeneratesTrackingCode;
use Armincms\Contract\Concerns\InteractsWithWidgets;
use Exception;
use Illuminate\Http\Request;
use Zareismail\Markable\HasDraft;
use Zareismail\Markable\HasPending;
use Zareismail\Markable\Markable;

class ArminpayTransaction extends Model implements Billing
{
    use GeneratesTrackingCode;
    use HasCancellation;
    use HasDraft;
    use HasFails;
    use HasPending;
    use HasSuccess;
    use InteractsWithWidgets;
    use Markable;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'tracking_code';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'payload' => 'json',
        'created_at' => 'datetime',
    ];

    /**
     * Generate new random code.
     *
     * @return string
     */
    public function generateRandomCode(): string
    {
        return (string) rand(999999999, 9999999999);
    }

    public function referenceNumber()
    {
        return $this->reference_number;
    }

    /**
     * Query the related Gateway.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gateway()
    {
        return $this->belongsTo(ArminpayGateway::class);
    }

    /**
     * Query the related Billable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function billable()
    {
        return $this->morphTo();
    }

    /**
     * Set a given JSON attribute on the payload attribute.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setPayload($key, $value = null)
    {
        return $this->fillJsonAttribute("payload->{$key}", $value);
    }

    /**
     * Get the payload value with the given key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return $this
     */
    public function getPayload($key, $default = null)
    {
        return data_get($this->payload, $key, $default);
    }

    /**
     * The payment amount.
     *
     * @return float
     */
    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * The payment currency.
     *
     * @return float
     */
    public function currency(): string
    {
        return $this->currency;
    }

    /**
     * Return the path that should be called after the payment.
     *
     * @return float
     */
    public function callback(): string
    {
        return route('arminpay.verify', $this->trackingCode());
    }

    /**
     * The payment payload.
     *
     * @return float
     */
    public function payload(): array
    {
        return (array) $this->payload;
    }

    /**
     * Get the billing identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->tracking_code;
    }

    /**
     * Verify paymet for the given request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     *
     * @throws \Exception
     */
    public function verify(Request $request)
    {
        try {
            return $this->closeViaReferenceNumber($this->gateway->createDriver()->verify($request, $this));
        } catch (Exception $exception) {
            $this->closeViaException($exception);
        }
    }

    public function closeViaReferenceNumber(string $referenceNumber)
    {
        $this->fillReferenceNumber($referenceNumber)->asSuccessed();

        return $this;
    }

    public function fillReferenceNumber(string $referenceNumber)
    {
        return $this->forceFill(['reference_number' => $referenceNumber]);
    }

    public function closeViaException(Exception $exception)
    {
        $this->fillException($exception)->asFailed();

        return $this;
    }

    public function fillException(Exception $exception)
    {
        return $this->forceFill(['exception' => $exception]);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new TransactionCollection($models);
    }

    /**
     * Serialize the model to pass into the client view.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForDetailWidget($request)
    {
        return [
            'trackingCode' => $this->trackingCode(),
            'referenceNumber' => $this->referenceNumber(),
            'state' => $this->marked_as,
            'date' => $this->created_at->format('Y-m-d h:m:s'),
        ];
    }
}
