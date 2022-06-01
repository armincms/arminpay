<?php

use Armincms\Arminpay\Nova\Gateway; 
use Armincms\Arminpay\Nova\Transaction; 

return [
    'resources' => [
        Gateway::class => Gateway::class, 
        Transaction::class => Transaction::class, 
    ], 
];