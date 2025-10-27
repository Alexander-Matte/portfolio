<?php

namespace App\State\Processors;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Note;
use App\Security\PlaygroundUser;
use Symfony\Bundle\SecurityBundle\Security;

class NoteProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof Note) {
            $user = $this->security->getUser();

            if (!$user instanceof PlaygroundUser) {
                throw new \RuntimeException('User must be authenticated');
            }

            $username = $user->getUserIdentifier();
            $data->setUserId($username);

            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}

