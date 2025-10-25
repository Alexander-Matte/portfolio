<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TaskRepository;
use App\State\TaskProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(name: 'idx_user_id', columns: ['user_id'])]
#[ORM\Index(name: 'idx_completed', columns: ['completed'])]
#[ORM\Index(name: 'idx_created_at', columns: ['created_at'])]
#[ApiResource(
    operations: [
        new GetCollection(
            description: 'Get all tasks for the authenticated user'
        ),
        new Post(
            description: 'Create a new task',
            processor: TaskProcessor::class
        ),
        new Get(
            description: 'Get a single task'
        ),
        new Put(
            description: 'Update a task (full update)',
            processor: TaskProcessor::class
        ),
        new Patch(
            description: 'Partially update a task',
            processor: TaskProcessor::class
        ),
        new Delete(
            description: 'Delete a task'
        )
    ],
    paginationEnabled: true,
    paginationItemsPerPage: 30,
    normalizationContext: ['groups' => ['task:read']],
    denormalizationContext: ['groups' => ['task:write']]
)]
#[ApiFilter(BooleanFilter::class, properties: ['completed'])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'updatedAt'])]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['task:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Title is required')]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Title must be at least {{ limit }} characters',
        maxMessage: 'Title cannot be longer than {{ limit }} characters'
    )]
    #[Groups(['task:read', 'task:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        max: 1000,
        maxMessage: 'Description cannot be longer than {{ limit }} characters'
    )]
    #[Groups(['task:read', 'task:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['task:read', 'task:write'])]
    private bool $completed = false;

    #[ORM\Column(length: 75)]
    #[Groups(['task:read'])]
    private ?string $userId = null;

    #[ORM\Column]
    #[Groups(['task:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['task:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->completed = false;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): static
    {
        $this->userId = $userId;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
