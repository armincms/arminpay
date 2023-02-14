<?php

namespace Armincms\Arminpay\Policies;

use Armincms\Contract\Policies\Policy;
use Armincms\Contract\Policies\SoftDeletes;

class Gateway extends Policy
{
    use SoftDeletes;
}
