<?php

namespace App\Entity;

use App\Repository\PodcastReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PodcastReviewRepository::class)
 */
class PodcastReview
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("podcast")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Podcast::class, inversedBy="ReviewList")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PodcastId;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ReviewList")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("podcast")
     */
    private $UserId;

    /**
     * @ORM\Column(type="float")
     * @Groups("podcast")
     */
    private $Rating;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?float
    {
        return $this->Rating;
    }

    public function setRating(float $Rating): self
    {
        $this->Rating = $Rating;

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
