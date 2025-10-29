<?php

// src/State/Providers/TaskProvider.php
namespace App\State\Providers;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class TaskProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Task|array|null
    {
        $user = $this->security->getUser();
        if (!$user) {
            return $operation instanceof GetCollection ? [] : null;
        }

        // Collection
        if ($operation instanceof GetCollection) {
            return $this->em->getRepository(Task::class)
                ->findBy(['userId' => $user->getUserIdentifier()]);
        }

        // Item
        if ($operation instanceof Get) {
            $id = $uriVariables['id'] ?? null;
            $task = $this->em->getRepository(Task::class)
                ->find($id);

            // Only return if it belongs to this user
            if ($task && $task->getUserId() === $user->getUserIdentifier()) {
                return $task;
            }

            return null; // Not found / forbidden
        }

        return null;
    }

}
