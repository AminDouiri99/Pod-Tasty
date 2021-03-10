<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlaylistRepository::class)
 */
class Playlist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="This field should not be blank, please give your playlist a name")
     */
    private $PlaylistName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="This field should not be blank, describe your playlist")
     * @Assert\Length ( min = 10 ,minMessage = "The playlist description must be at least {{ limit }} characters long")
     */
    private $PlaylistDescription;

    /**
     * @ORM\Column(type="datetime")
     */
    private $PlaylistCreationDate;



    /**
     * @ORM\OneToMany(targetEntity=Podcast::class, mappedBy="PlaylistId")
     */
    private $PodcastList;

    /**
     * @ORM\ManyToOne(targetEntity=Channel::class, inversedBy="Playlists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ChannelId;



    public function __construct()
    {
        $this->PodcastList = new ArrayCollection();
        $this->PlaylistCreationDate = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaylistName(): ?string
    {
        return $this->PlaylistName;
    }

    public function setPlaylistName(string $PlaylistName): self
    {
        $this->PlaylistName = $PlaylistName;

        return $this;
    }

    public function getPlaylistDescription(): ?string
    {
        return $this->PlaylistDescription;
    }

    public function setPlaylistDescription(string $PlaylistDescription): self
    {
        $this->PlaylistDescription = $PlaylistDescription;

        return $this;
    }

    public function getPlaylistCreationDate(): ?\DateTimeInterface
    {
        return $this->PlaylistCreationDate;
    }

    public function setPlaylistCreationDate(\DateTimeInterface $PlaylistCreationDate): self
    {
        $this->PlaylistCreationDate = $PlaylistCreationDate;

        return $this;
    }

    /**
     * @return Collection|Podcast[]
     */
    public function getPodcastList(): Collection
    {
        return $this->PodcastList;
    }

    public function addPodcastList(Podcast $podcastList): self
    {
        if (!$this->PodcastList->contains($podcastList)) {
            $this->PodcastList[] = $podcastList;
            $podcastList->setPlaylistId($this);
        }

        return $this;
    }

    public function removePodcastList(Podcast $podcastList): self
    {
        if ($this->PodcastList->removeElement($podcastList)) {
            // set the owning side to null (unless already changed)
            if ($podcastList->getPlaylistId() === $this) {
                $podcastList->setPlaylistId(null);
            }
        }

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




}

