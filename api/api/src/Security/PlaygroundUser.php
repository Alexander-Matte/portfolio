<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class PlaygroundUser implements UserInterface
{
    public function __construct(
        private string $username,
        private string $token
    ) {
    }

    public function getRoles(): array
    {
        return ['ROLE_PLAYGROUND_USER'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
