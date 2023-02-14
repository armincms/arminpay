<?php

namespace Armincms\Arminpay\Models;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Database\Eloquent\SoftDeletes;

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
