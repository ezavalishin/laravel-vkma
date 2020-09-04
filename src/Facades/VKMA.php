<?php

namespace ezavalishin\VKMA\Facades;

use Illuminate\Support\Facades\Facade;

class VKMA extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'vkma';
    }
}
