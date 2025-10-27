<?php

namespace App\State\Providers;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\Factory\ResourceNameCollectionFactoryInterface;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ApiEndpoints;

/**
 * @implements ProviderInterface<ApiEndpoints>
 */
class ApiEndpointsProvider implements ProviderInterface
{
    public function __construct(
        private ResourceNameCollectionFactoryInterface $resourceNameCollectionFactory,
        private ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        private PropertyNameCollectionFactoryInterface $propertyNameCollectionFactory,
        private PropertyMetadataFactoryInterface $propertyMetadataFactory,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ApiEndpoints
    {
        $dto = new ApiEndpoints();
        $endpoints = [];
        $resourceNames = $this->resourceNameCollectionFactory->create();

        foreach ($resourceNames as $resourceName) {
            $resourceMetadataCollection = $this->resourceMetadataCollectionFactory->create($resourceName);

            foreach ($resourceMetadataCollection as $resourceMetadata) {
                $shortName = $resourceMetadata->getShortName();
                
                if (in_array($shortName, ['Error', 'ConstraintViolation', 'ConstraintViolationList'])) {
                    continue;
                }
                
                if (!isset($endpoints[$shortName])) {
                    $endpoints[$shortName] = [
                        'name' => $shortName,
                        'operations' => [],
                        'properties' => []
                    ];
                }

                foreach ($resourceMetadata->getOperations() as $op) {
                    $method = $this->getHttpMethod($op);
                    $path = str_replace('{._format}', '', $op->getUriTemplate() ?? '');
                    
                    $endpoints[$shortName]['operations'][] = [
                        'method' => $method,
                        'path' => '/api' . $path,
                        'description' => $op->getDescription()
                    ];
                }
                
                if (empty($endpoints[$shortName]['properties'])) {
                    try {
                        $propertyNames = $this->propertyNameCollectionFactory->create($resourceName);
                        
                        foreach ($propertyNames as $propertyName) {
                            $propertyMetadata = $this->propertyMetadataFactory->create($resourceName, $propertyName);
                            
                            $types = $propertyMetadata->getBuiltinTypes();
                            $type = !empty($types) ? $types[0]->getBuiltinType() : null;
                            
                            $endpoints[$shortName]['properties'][] = [
                                'name' => $propertyName,
                                'type' => $type,
                                'required' => $propertyMetadata->isRequired(),
                                'readable' => $propertyMetadata->isReadable(),
                                'writable' => $propertyMetadata->isWritable(),
                            ];
                        }
                    } catch (\Exception $e) {
                    }
                }
            }
        }
        
        $dto->endpoints = array_values($endpoints);
        $dto->generatedAt = (new \DateTimeImmutable())->format('c');
        
        return $dto;
    }

    private function getHttpMethod($operation): string
    {
        $class = get_class($operation);
        $shortClass = substr($class, strrpos($class, '\\') + 1);
        
        return match($shortClass) {
            'GetCollection', 'Get' => 'GET',
            'Post' => 'POST',
            'Put' => 'PUT',
            'Patch' => 'PATCH',
            'Delete' => 'DELETE',
            default => 'UNKNOWN'
        };
    }
}

