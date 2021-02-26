<?php

namespace App\Entity;

use App\Repository\ChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChannelRepository::class)
 */
class Channel
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
    private $ChannelName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ChannelDescription;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ChannelCreationDate;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="ChannelId", cascade={"persist", "remove"})
     */
    private $UserId;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="ChannelsSubscribed")
     */
    private $UserList;

    public function __construct()
    {
        $this->UserList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChannelName(): ?string
    {
        return $this->ChannelName;
    }

    public function setChannelName(string $ChannelName): self
    {
        $this->ChannelName = $ChannelName;

        return $this;
    }

    public function getChannelDescription(): ?string
    {
        return $this->ChannelDescription;
    }

    public function setChannelDescription(string $ChannelDescription): self
    {
        $this->ChannelDescription = $ChannelDescription;

        return $this;
    }

    public function getChannelCreationDate(): ?\DateTimeInterface
    {
        return $this->ChannelCreationDate;
    }

    public function setChannelCreationDate(\DateTimeInterface $ChannelCreationDate): self
    {
        $this->ChannelCreationDate = $ChannelCreationDate;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): self
    {
        // unset the owning side of the relation if necessary
        if ($UserId === null && $this->UserId !== null) {
            $this->UserId->setChannelId(null);
        }

        // set the owning side of the relation if necessary
        if ($UserId !== null && $UserId->getChannelId() !== $this) {
            $UserId->setChannelId($this);
        }

        $this->UserId = $UserId;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserList(): Collection
    {
        return $this->UserList;
    }

    public function addUserList(User $userList): self
    {
        if (!$this->UserList->contains($userList)) {
            $this->UserList[] = $userList;
            $userList->addChannelsSubscribed($this);
        }

        return $this;
    }

    public function removeUserList(User $userList): self
    {
        if ($this->UserList->removeElement($userList)) {
            $userList->removeChannelsSubscribed($this);
        }

        return $this;
    }
}
