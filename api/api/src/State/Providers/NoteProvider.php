<?php
// src/State/Providers/NoteProvider.php
namespace App\State\Providers;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class NoteProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Note|array|null
    {
        $user = $this->security->getUser();
        if (!$user) {
            return $operation instanceof GetCollection ? [] : null;
        }

        // Collection
        if ($operation instanceof GetCollection) {
            return $this->em->getRepository(Note::class)
                ->findBy(['userId' => $user->getUserIdentifier()]);
        }

        // Item
        if ($operation instanceof Get) {
            $id = $uriVariables['id'] ?? null;
            $note = $this->em->getRepository(Note::class)
                ->find($id);

            // Only return if it belongs to this user
            if ($note && $note->getUserId() === $user->getUserIdentifier()) {
                return $note;
            }

            return null; // Not found / forbidden
        }

        return null;
    }

}
