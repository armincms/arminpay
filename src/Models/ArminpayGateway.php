<?php

namespace Armincms\Arminpay\Models;

use Armincms\Contract\Concerns\Configurable; 
use Armincms\Contract\Concerns\InteractsWithMedia; 
use Armincms\Contract\Contracts\HasMedia; 
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;

class ArminpayGateway extends Model implements HasMedia
{   
    use Configurable;
    use HasCheckout;
    use InteractsWithMedia;
    use InteractsWithTargomaan;
    use InteractsWithTransactions;
    
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
    ];   
    
    /**
     * Query that where enabled.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query 
     * @return \Illuminate\Database\Eloquent\Builder        
     */
    public function scopeEnable($query)
    {   
        return $this->whereEnabled(1);
    }
}
