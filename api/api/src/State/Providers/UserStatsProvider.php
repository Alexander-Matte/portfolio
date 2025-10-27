<?php

namespace App\State\Providers;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\UserStats;
use App\Repository\UserStatsRepository;
use App\Security\PlaygroundUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
/**
 * @implements ProviderInterface<UserStats>
 */
final class UserStatsProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private UserStatsRepository $repository
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?UserStats
    {
        $user = $this->security->getUser();

        if (!$user instanceof PlaygroundUser) {
            throw new HttpException(
                Response::HTTP_UNAUTHORIZED,
                'Full authentication is required to access this resource.'
            );
        }

        $username = $user->getUserIdentifier();

        // Return stats for this user
        return $this->repository->findOneBy(['username' => $username]);
    }
}
