<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use App\Interfaces\ActivityLoggableInterface;
use App\Repository\NoteRepository;
use App\State\Processors\NoteProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
#[ORM\Index(name: 'idx_user_id', columns: ['user_id'])]
#[ORM\Index(name: 'idx_created_at', columns: ['created_at'])]
#[ApiResource(
    operations: [
        new GetCollection(
            description: 'Get all notes for the authenticated user'
        ),
        new Post(
            description: 'Create a new note',
            processor: NoteProcessor::class
        ),
        new Get(
            description: 'Get a single note'
        ),
        new Put(
            description: 'Update a note (full update)',
            processor: NoteProcessor::class
        ),
        new Delete(
            description: 'Delete a note'
        ),
        new Patch(
            description: 'Partially update a note',
            processor: NoteProcessor::class
        )
    ],
    normalizationContext: ['groups' => ['note:read']],
    denormalizationContext: ['groups' => ['note:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 30,
    security: "is_granted('ROLE_PLAYGROUND_USER')"
)]
#[ApiFilter(OrderFilter::class, properties: ['createdAt'])]
class Note implements ActivityLoggableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['note:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Title is required')]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Title must be at least {{ limit }} characters',
        maxMessage: 'Title cannot be longer than {{ limit }} characters'
    )]
    #[Groups(['note:read', 'note:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Content is required')]
    #[Assert\Length(
        min: 1,
        max: 5000,
        minMessage: 'Content must be at least {{ limit }} characters',
        maxMessage: 'Content cannot be longer than {{ limit }} characters'
    )]
    #[Groups(['note:read', 'note:write'])]
    private ?string $content = null;

    #[ORM\Column(length: 75)]
    #[Groups(['note:read'])]
    private ?string $userId = null;

    #[ORM\Column]
    #[Groups(['note:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getActivityMessage(string $method, string $type): string
    {
        $titles = [
            'post' => 'created',
            'put' => 'updated',
            'patch' => 'updated',
            'delete' => 'deleted',
        ];

        $action = $titles[strtolower($method)] ?? 'modified';

        return "{$action} the {$type} “{$this->getTitle()}”";
    }

    public function getActivityPayload(string $shortName): array
    {
        return [
            "{$shortName}_id" => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
        ];
    }
}
