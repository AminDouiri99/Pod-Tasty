<?php

namespace App\Entity;

use App\Repository\ChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Assert\NotBlank (message="This field should not be blank, please give your channel a name")
     * @Assert\NotNull (message="This field should not be blank, please give your channel a name")
     * @Assert\Length (min=5 , max=15, minMessage="Channel name is too short try something else" , maxMessage="You've exceeded teh maximum allowed characters , try something shorter")
     */
    private $ChannelName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="This field should not be blank, describe your channel")
     * @Assert\Length ( min = 5,max=25,maxMessage="The channel description cannot be too long" ,minMessage = "The channel description must be at least {{ limit }} characters long")
     */
    private $ChannelDescription;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ChannelCreationDate;


    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="ChannelId", cascade={"persist"})
     */
    private $UserId;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="ChannelsSubscribed")
     */
    private $UserList;

    /**
     * @ORM\OneToMany(targetEntity=Playlist::class, mappedBy="ChannelId", orphanRemoval=true)
     */
    private $Playlists;

    /**
     * @ORM\Column(type="integer")
     */
    private $ChannelStatus;

    public function __construct()
    {
        $this->UserList = new ArrayCollection();

        $this->ChannelCreationDate = new \DateTime('now');
        $this->Playlists = new ArrayCollection();

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

    /**
     * @return Collection|Playlist[]
     */
    public function getPlaylists(): Collection
    {
        return $this->Playlists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->Playlists->contains($playlist)) {
            $this->Playlists[] = $playlist;
            $playlist->setChannelId($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->Playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getChannelId() === $this) {
                $playlist->setChannelId(null);
            }
        }

        return $this;
    }

    public function getChannelStatus(): ?int
    {
        return $this->ChannelStatus;
    }

    public function setChannelStatus(?int $ChannelStatus): self
    {
        $this->ChannelStatus = $ChannelStatus;

        return $this;
    }
}
