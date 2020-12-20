<?php

namespace Armincms\Arminpay\Models;

use Illuminate\Database\Eloquent\{Model as LaravelModel, SoftDeletes};
use Armincms\Orderable\Orderable;

class Model extends LaravelModel
{
	use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];   
}
