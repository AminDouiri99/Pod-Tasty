<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("reclamations")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("reclamations")
     */
    private $Type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("reclamations")
     */
    private $Description;

    /**
     * @ORM\Column(type="integer")
     * @Groups("reclamations")
     */
    private $Status;

    /**
     * @ORM\ManyToOne(targetEntity=Podcast::class, inversedBy="ReclamationList")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("reclamations")
     */
    private $PodcastId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ReclamationList")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("reclamations")
     */
    private $UserId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->Status;
    }

    public function setStatus(int $Status): self
    {
        $this->Status = $Status;

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
