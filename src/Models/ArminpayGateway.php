<?php

namespace Armincms\Arminpay\Models;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Armincms\Concerns\HasMediaTrait;

class ArminpayGateway extends Model implements HasMedia
{   
    use HasMediaTrait, InteractsWithTargomaan, InteractsWithTransactions, HasCheckout;
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    	'config' => 'json',
    ];   
    
    protected $medias = [
        'logo' => [ 
            'disk'  => 'armin.image',
            'conversions' => [
                'common'
            ]
        ], 
    ]; 

    /**
     * Set a given JSON attribute on the config attribute.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setConfig($key, $value = null)
    {
    	return $this->fillJsonAttribute("config->{$key}", $value);
    }

    /**
     * Get the config value with the given key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return $this
     */
    public function getConfig($key, $default = null)
    {
    	return data_get($this->config, $key, $default);
    }

    /**
     * Query that where enabled.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query 
     * @return \Illuminate\Database\Eloquent\Builder        
     */
    public function scopeEnabled($query)
    {   
        return $this->whereEnabled(1);
    }
}
