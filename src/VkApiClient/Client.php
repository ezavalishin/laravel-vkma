<?php

namespace ezavalishin\VKMA\VkApiClient;

use VK\Client\VKApiClient;

class Client
{
    protected int $appId;
    protected string $serviceKey;
    protected VKApiClient $client;

    public function __construct(int $appId, string $serviceKey, string $locale)
    {
        $this->appId = $appId;
        $this->serviceKey = $serviceKey;

        $this->client = new VKApiClient('5.101', $locale);
    }

    public function getUser(int $vkUserId, array $fields): array
    {
        return $this->getUsers([$vkUserId], $fields)[0];
    }

    public function getUsers(array $vkUserIds, array $fields): array
    {
        return $this->client->users()->get($this->serviceKey, [
            'user_ids' => $vkUserIds,
            'fields' => $fields,
        ]);
    }
}
