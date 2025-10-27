<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\UserStatsRepository;
use App\State\Providers\UserStatsProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserStatsRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/stats/me',
            description: 'Get current user statistics',
            provider: UserStatsProvider::class
        )
    ]
)]
class UserStats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 75, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private int $requestMade = 0;

    #[ORM\Column]
    private int $successfulRequests = 0;

    #[ORM\Column]
    private int $totalResponseTime = 0;

    #[ORM\Column]
    private int $tasksCreated = 0;

    #[ORM\Column]
    private int $tasksCompleted = 0;

    #[ORM\Column]
    private int $notesCreated = 0;

    #[ORM\Column(length: 50)]
    private string $rank = 'Beginner';

    #[ORM\Column(type: Types::JSON)]
    private array $badges = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastActivity = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->lastActivity = new \DateTimeImmutable();
        $this->requestMade = 0;
        $this->successfulRequests = 0;
        $this->totalResponseTime = 0;
        $this->tasksCreated = 0;
        $this->tasksCompleted = 0;
        $this->notesCreated = 0;
        $this->rank = 'Beginner';
        $this->badges = [];
    }

    #[ORM\PreUpdate]
    public function updateLastActivity(): void
    {
        $this->lastActivity = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getRequestMade(): int
    {
        return $this->requestMade;
    }

    public function setRequestMade(int $requestMade): static
    {
        $this->requestMade = $requestMade;

        return $this;
    }

    public function incrementRequestsMade(): static
    {
        $this->requestMade++;

        return $this;
    }

    public function getSuccessfulRequests(): int
    {
        return $this->successfulRequests;
    }

    public function setSuccessfulRequests(int $successfulRequests): static
    {
        $this->successfulRequests = $successfulRequests;

        return $this;
    }

    public function incrementSuccessfulRequests(): static
    {
        $this->successfulRequests++;

        return $this;
    }

    public function getTotalResponseTime(): int
    {
        return $this->totalResponseTime;
    }

    public function setTotalResponseTime(int $totalResponseTime): static
    {
        $this->totalResponseTime = $totalResponseTime;

        return $this;
    }

    public function addResponseTime(int $responseTime): static
    {
        $this->totalResponseTime += $responseTime;

        return $this;
    }

    public function getAverageResponseTime(): int
    {
        if ($this->requestMade === 0) {
            return 0;
        }

        return (int) ($this->totalResponseTime / $this->requestMade);
    }

    public function getSuccessRate(): float
    {
        if ($this->requestMade === 0) {
            return 0.0;
        }

        return ($this->successfulRequests / $this->requestMade) * 100;
    }

    public function getTasksCreated(): int
    {
        return $this->tasksCreated;
    }

    public function setTasksCreated(int $tasksCreated): static
    {
        $this->tasksCreated = $tasksCreated;

        return $this;
    }

    public function incrementTasksCreated(): static
    {
        $this->tasksCreated++;

        return $this;
    }

    public function getTasksCompleted(): int
    {
        return $this->tasksCompleted;
    }

    public function setTasksCompleted(int $tasksCompleted): static
    {
        $this->tasksCompleted = $tasksCompleted;

        return $this;
    }

    public function incrementTasksCompleted(): static
    {
        $this->tasksCompleted++;

        return $this;
    }

    public function getNotesCreated(): int
    {
        return $this->notesCreated;
    }

    public function setNotesCreated(int $notesCreated): static
    {
        $this->notesCreated = $notesCreated;

        return $this;
    }

    public function incrementNotesCreated(): static
    {
        $this->notesCreated++;

        return $this;
    }

    public function getRank(): string
    {
        return $this->rank;
    }

    public function setRank(string $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getBadges(): array
    {
        return $this->badges;
    }

    public function setBadges(array $badges): static
    {
        $this->badges = $badges;

        return $this;
    }

    public function addBadge(array $badge): static
    {
        $this->badges[] = $badge;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastActivity(): ?\DateTimeImmutable
    {
        return $this->lastActivity;
    }

    public function setLastActivity(\DateTimeImmutable $lastActivity): static
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }
}
