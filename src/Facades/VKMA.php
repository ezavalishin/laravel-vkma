<?php

namespace ezavalishin\VKMA\Facades;

use ezavalishin\VKMA\VkApiClient\Client;
use Illuminate\Support\Facades\Facade;

/**
 * Class VKMA.
 *
 * @method static Client getClient
 */
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
