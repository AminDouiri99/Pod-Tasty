<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PlaylistRepository::class)
 * @Vich\Uploadable
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
     * @ORM\Column(type="string", length=255, nullable=true)
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


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="playlist", fileNameProperty="imageName")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $imageName;


    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;




    public function __construct()
    {
        $this->PodcastList = new ArrayCollection();
        $this->PlaylistCreationDate = new \DateTime('now');
        $this->updatedAt= new \DateTime('now');
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

    public function getPlaylistImage(): ?string
    {
        return $this->PlaylistImage;
    }

    public function setPlaylistImage(?string $PlaylistImage): self
    {
        $this->PlaylistImage = $PlaylistImage;

        return $this;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }



}

