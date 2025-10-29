<?php

namespace App\Interfaces;

interface ActivityLoggableInterface
{
    public function getActivityMessage(string $method, string $type): string;
    public function getActivityPayload(string $shortName): array;
}
