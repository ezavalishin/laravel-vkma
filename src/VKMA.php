<?php

namespace ezavalishin\VKMA;

use ezavalishin\VKMA\VkApiClient\Client;

class VKMA
{
    protected Client $client;

    public function __construct(int $appId, string $serviceKey, string $locale)
    {
        $this->client = new Client($appId, $serviceKey, $locale);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
