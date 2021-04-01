<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $NotificationDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NotificationTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NotificationDescription;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isViewed;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="NotificationList")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotificationDate(\App\Controller\DateTime $param): ?\DateTimeInterface
    {
        return $this->NotificationDate;
    }

    public function setNotificationDate(\DateTimeInterface $NotificationDate): self
    {
        $this->NotificationDate = $NotificationDate;

        return $this;
    }

    public function getNotificationTitle(): ?string
    {
        return $this->NotificationTitle;
    }

    public function setNotificationTitle(string $NotificationTitle): self
    {
        $this->NotificationTitle = $NotificationTitle;

        return $this;
    }

    public function getNotificationDescription(): ?string
    {
        return $this->NotificationDescription;
    }

    public function setNotificationDescription(string $NotificationDescription): self
    {
        $this->NotificationDescription = $NotificationDescription;

        return $this;
    }

    public function getIsViewed(): ?bool
    {
        return $this->isViewed;
    }

    public function setIsViewed(bool $isViewed): self
    {
        $this->isViewed = $isViewed;

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
