<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\Providers\ApiEndpointsProvider;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/endpoints',
            description: 'Get all available API endpoints with their operations and properties',
            provider: ApiEndpointsProvider::class
        )
    ],
    paginationEnabled: false
)]
class ApiEndpoints
{
    public array $endpoints = [];
    public string $generatedAt = '';
}

