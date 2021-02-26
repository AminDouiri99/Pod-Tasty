<?php

namespace App\Entity;

use App\Repository\PodcastCommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PodcastCommentRepository::class)
 */
class PodcastComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CommentText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CommentDate;

    /**
     * @ORM\ManyToOne(targetEntity=Podcast::class, inversedBy="CommentList")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PodcastId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="CommentList")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentText(): ?string
    {
        return $this->CommentText;
    }

    public function setCommentText(string $CommentText): self
    {
        $this->CommentText = $CommentText;

        return $this;
    }

    public function getCommentDate(): ?\DateTimeInterface
    {
        return $this->CommentDate;
    }

    public function setCommentDate(\DateTimeInterface $CommentDate): self
    {
        $this->CommentDate = $CommentDate;

        return $this;
    }

    public function getPodcastId(): ?Podcast
    {
        return $this->PodcastId;
    }

    public function setPodcastId(?Podcast $PodcastId): self
    {
        $this->PodcastId = $PodcastId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }
}
