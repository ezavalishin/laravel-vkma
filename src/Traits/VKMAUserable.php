<?php

namespace ezavalishin\VKMA\Traits;

trait VKMAUserable
{
    public function getVkUserId(): int
    {
        return $this->{config('vkma.options.vk_id_key')};
    }
}
