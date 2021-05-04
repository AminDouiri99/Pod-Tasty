<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("comments")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your Email!")
     * @Groups("users")
     */
    private $UserEmail;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Assert\NotBlank(message="Please enter your Password!")
     */
    private $UserPassword;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;
    /**
     * @ORM\OneToOne(targetEntity=UserInfo::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups("comments")
     */
    private $UserInfoId;



        /**
     * @ORM\OneToOne(targetEntity=Channel::class, inversedBy="UserId", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups("users")
     */
    private $ChannelId;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="UserId")
     */
    private $ReclamationList;

    /**
     * @ORM\ManyToMany(targetEntity=Podcast::class, inversedBy="usersList")
     */
    private $PodcastsFavorite;

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

    /**
     * @ORM\Column(type="boolean")
     * @Groups("users")
     */
    private $DesactiveAccount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $githubId;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user")
     */
    private $posts;

    public function __construct()
    {
        $this->ReclamationList = new ArrayCollection();
        $this->NotificationList = new ArrayCollection();
        $this->ChannelsSubscribed = new ArrayCollection();
        $this->CommentList = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id)
    {
        $this->id = $id;
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
     * @return Collection|Podcast[]
     */
    public function getPodcastsFavorite(): Collection
    {
        return $this->PodcastsFavorite;
    }

    public function addPodcastsFavorite(Podcast $PodcastsFavorite): self
    {
        if (!$this->PodcastsFavorite->contains($PodcastsFavorite)) {
            $this->PodcastsFavorite[] = $PodcastsFavorite;
            $PodcastsFavorite->addUsersList($this);
        }

        return $this;
    }

    public function removePodcastsFavorite(Podcast $PodcastsFavorite): self
    {
        if ($this->PodcastsFavorite->removeElement($PodcastsFavorite)) {
            // set the owning side to null (unless already changed)
            if ($PodcastsFavorite->getUsersList()->contains($this)) {
                $PodcastsFavorite->removeUsersList($this);
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
        if($this->isAdmin==true){
            return ["admin"];
        }
        if($this->DesactiveAccount==true){
            return ["disabled"];
        }
        else
            return ["user"];
    }

    public function getPassword()
    {
        return $this->UserPassword;
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

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function getDesactiveAccount(): ?bool
    {
        return $this->DesactiveAccount;
    }

    public function setDesactiveAccount(bool $DesactiveAccount): self
    {
        $this->DesactiveAccount = $DesactiveAccount;

        return $this;
    }

    public function getGithubId(): ?string
    {
        return $this->githubId;
    }

    public function setGithubId(?string $githubId): self
    {
        $this->githubId = $githubId;

        return $this;
    }
}