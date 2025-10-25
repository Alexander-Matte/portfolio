<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\GlobalCounterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GlobalCounterRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/counter',
            description: 'Get the global counter value'
        ),
        new Post(
            uriTemplate: '/counter/increment',
            description: 'Increment the global counter (broadcasts via Mercure)'
        )
    ]
)]
class GlobalCounter
{
    #[ORM\Id]
    #[ORM\Column]
    private int $id = 1; // Single record only

    #[ORM\Column]
    private int $value = 0;

    #[ORM\Column]
    private int $totalUsers = 0;

    #[ORM\Column]
    private int $activeUsers = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastUpdated = null;

    public function __construct()
    {
        $this->lastUpdated = new \DateTimeImmutable();
        $this->value = 0;
        $this->totalUsers = 0;
        $this->activeUsers = 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;
        $this->lastUpdated = new \DateTimeImmutable();

        return $this;
    }

    public function increment(): static
    {
        $this->value++;
        $this->lastUpdated = new \DateTimeImmutable();

        return $this;
    }

    public function getTotalUsers(): int
    {
        return $this->totalUsers;
    }

    public function setTotalUsers(int $totalUsers): static
    {
        $this->totalUsers = $totalUsers;

        return $this;
    }

    public function getActiveUsers(): int
    {
        return $this->activeUsers;
    }

    public function setActiveUsers(int $activeUsers): static
    {
        $this->activeUsers = $activeUsers;

        return $this;
    }

    public function getLastUpdated(): ?\DateTimeImmutable
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(\DateTimeImmutable $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }
}
