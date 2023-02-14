<?php

namespace Armincms\Arminpay\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Manager;

class ResolvingArminpay
{
    use Dispatchable;

    /**
     * The Gateway manager instance.
     *
     * @var \Illuminate\Support\Manager
     */
    public $manager;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Support\Manager  $manager
     * @return void
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }
}
