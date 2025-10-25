<?php

namespace App\Security;

use App\Repository\PlaygroundSessionRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private PlaygroundSessionRepository $sessionRepository
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $session = $this->sessionRepository->findOneBy(['token' => $accessToken]);

        if (null === $session) {
            throw new BadCredentialsException('Invalid token.');
        }

        if (!$session->isActive()) {
            throw new BadCredentialsException('Session is not active.');
        }

        if ($session->getExpireAt() < new \DateTimeImmutable()) {
            throw new BadCredentialsException('Token has expired.');
        }

        return new UserBadge(
            $session->getUsername(),
            fn (string $userIdentifier) => new PlaygroundUser($userIdentifier, $session->getToken())
        );
    }
}

