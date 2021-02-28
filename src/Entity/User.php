<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
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
    private $UserEmail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $UserPassword;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;

    /**
     * @ORM\OneToOne(targetEntity=UserInfo::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $UserInfoId;

    /**
     * @ORM\OneToOne(targetEntity=Channel::class, inversedBy="UserId", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $ChannelId;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="UserId")
     */
    private $ReclamationList;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="UserId")
     */
    private $NotificationList;

    /**
     * @ORM\ManyToMany(targetEntity=Channel::class, inversedBy="UserList")
     */
    private $ChannelsSubscribed;

    /**
     * @ORM\OneToMany(targetEntity=PodcastComment::class, mappedBy="UserId")
     */
    private $CommentList;

    public function __construct()
    {
        $this->ReclamationList = new ArrayCollection();
        $this->NotificationList = new ArrayCollection();
        $this->ChannelsSubscribed = new ArrayCollection();
        $this->CommentList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserEmail(): ?string
    {
        return $this->UserEmail;
    }

    public function setUserEmail(string $UserEmail): self
    {
        $this->UserEmail = $UserEmail;

        return $this;
    }

    public function getUserPassword(): ?string
    {
        return $this->UserPassword;
    }

    public function setUserPassword(string $UserPassword): self
    {
        $this->UserPassword = $UserPassword;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getUserInfoId(): ?UserInfo
    {
        return $this->UserInfoId;
    }

    public function setUserInfoId(UserInfo $UserInfoId): self
    {
        $this->UserInfoId = $UserInfoId;

        return $this;
    }

    public function getChannelId(): ?Channel
    {
        return $this->ChannelId;
    }

    public function setChannelId(?Channel $ChannelId): self
    {
        $this->ChannelId = $ChannelId;

        return $this;
    }

    /**
     * @return Collection|Reclamation[]
     */
    public function getReclamationList(): Collection
    {
        return $this->ReclamationList;
    }

    public function addReclamationList(Reclamation $reclamationList): self
    {
        if (!$this->ReclamationList->contains($reclamationList)) {
            $this->ReclamationList[] = $reclamationList;
            $reclamationList->setUserId($this);
        }

        return $this;
    }

    public function removeReclamationList(Reclamation $reclamationList): self
    {
        if ($this->ReclamationList->removeElement($reclamationList)) {
            // set the owning side to null (unless already changed)
            if ($reclamationList->getUserId() === $this) {
                $reclamationList->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotificationList(): Collection
    {
        return $this->NotificationList;
    }

    public function addNotificationList(Notification $notificationList): self
    {
        if (!$this->NotificationList->contains($notificationList)) {
            $this->NotificationList[] = $notificationList;
            $notificationList->setUserId($this);
        }

        return $this;
    }

    public function removeNotificationList(Notification $notificationList): self
    {
        if ($this->NotificationList->removeElement($notificationList)) {
            // set the owning side to null (unless already changed)
            if ($notificationList->getUserId() === $this) {
                $notificationList->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Channel[]
     */
    public function getChannelsSubscribed(): Collection
    {
        return $this->ChannelsSubscribed;
    }

    public function addChannelsSubscribed(Channel $channelsSubscribed): self
    {
        if (!$this->ChannelsSubscribed->contains($channelsSubscribed)) {
            $this->ChannelsSubscribed[] = $channelsSubscribed;
        }

        return $this;
    }

    public function removeChannelsSubscribed(Channel $channelsSubscribed): self
    {
        $this->ChannelsSubscribed->removeElement($channelsSubscribed);

        return $this;
    }

    /**
     * @return Collection|PodcastComment[]
     */
    public function getCommentList(): Collection
    {
        return $this->CommentList;
    }

    public function addCommentList(PodcastComment $commentList): self
    {
        if (!$this->CommentList->contains($commentList)) {
            $this->CommentList[] = $commentList;
            $commentList->setUserId($this);
        }

        return $this;
    }

    public function removeCommentList(PodcastComment $commentList): self
    {
        if ($this->CommentList->removeElement($commentList)) {
            // set the owning side to null (unless already changed)
            if ($commentList->getUserId() === $this) {
                $commentList->setUserId(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
