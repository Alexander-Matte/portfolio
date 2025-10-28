<?php

namespace App\State\Processors;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Note;
use App\Entity\Activity;
use App\Security\PlaygroundUser;
use App\Service\ActivityPublisher;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class NoteProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private ActivityPublisher $activityPublisher
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Note) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        $user = $this->security->getUser();
        if (!$user instanceof PlaygroundUser) {
            throw new HttpException(
                Response::HTTP_UNAUTHORIZED,
                'Full authentication is required to access this resource.'
            );
        }

        $username = $user->getUserIdentifier();
        $data->setUserId($username);

        // Persist the Note first
        $note = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        // Create Activity after Note is saved
        $activity = new Activity();
        $activity->setType('note.created');
        $activity->setUsername($username);
        $activity->setMessage("User: {$username} created note '{$note->getTitle()}'");
        $activity->setData([
            'note_id' => $note->getId(),
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
        ]);

        $this->activityPublisher->publish($activity);

        return $note;
    }
}
