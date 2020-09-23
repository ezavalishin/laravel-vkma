<?php

namespace ezavalishin\VKMA;

use ezavalishin\VKMA\Utils\LaunchParams;
use ezavalishin\VKMA\VkApiClient\Client;
use Illuminate\Http\Request;

class VKMA
{
    protected Client $client;
    protected Request $request;

    private static ?LaunchParams $launchParams = null;

    public function __construct(int $appId, string $serviceKey, string $locale, Request $request)
    {
        $this->client = new Client($appId, $serviceKey, $locale);

        if ($header = $request->header('Vk-Params')) {
            try {
                self::$launchParams = LaunchParams::fromParams($header);
            } catch (\Exception $e) {
            }
        }
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function launchParams(): ?LaunchParams
    {
        return self::$launchParams;
    }
}
