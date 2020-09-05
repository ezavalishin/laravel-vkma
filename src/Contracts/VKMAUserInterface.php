<?php

namespace ezavalishin\VKMA\Contracts;

interface VKMAUserInterface
{
    public function vkFieldsMap(): array;

    public function getVkUserId(): int;
}
