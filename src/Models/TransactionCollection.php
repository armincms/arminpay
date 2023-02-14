<?php

namespace Armincms\Arminpay\Models;

use Illuminate\Database\Eloquent\Collection;

class TransactionCollection extends Collection
{
    /**
     * Filters successful transctions.
     *
     * @return
     */
    public function successed()
    {
        return $this->filter->isSuccessed();
    }
}
