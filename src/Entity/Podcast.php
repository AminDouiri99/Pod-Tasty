<?php

namespace App\Entity;

use App\Repository\PodcastRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use symfony\component\serializer\annotation\groups;





/**
 * @ORM\Entity(repositoryClass=PodcastRepository::class)
 */
class Podcast
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups("podcast")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("podcast")

     */
    private $PodcastName;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Groups("podcast")

     *
     */
    private $currentlyLive;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Groups("podcast")
     */
    private $isBlocked;


    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Groups("podcast")
     */
    private $commentsAllowed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("podcast")
     */
    private $PodcastDescription;

    private $tags;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 400,
     *     minHeight = 200,
     *     maxHeight = 400
     * )
     * @Groups("podcast")
     */
    private $PodcastImage;
//
//    /**
//     * @ORM\Column(type="string", length=255)
//     */
//    private $PodcastGenre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("podcast")
     */
    private $PodcastViews;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("podcast")
     */
    private $currentlyWatching;


    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     * @Groups("podcast")
     */
    private $PodcastDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(
     *     mimeTypes = {"application/pdf", "application/x-audio"},
     *     mimeTypesMessage = "Please upload a valid Audio")
     * @Groups("podcast")
     */
    private $PodcastSource;

    /**
     * @ORM\ManyToOne(targetEntity=Playlist::class, inversedBy="PodcastList")
     * @ORM\JoinColumn(nullable=true)
     * @Groups("podcast")
     */
    private $PlaylistId;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="PodcastId")
     * @Groups("podcast")
     */
    private $ReclamationList;

    /**
     * @ORM\ManyToMany (targetEntity=User::class, mappedBy="PodcastsFavorite")
     * @Groups("podcast")
     */
    private $usersList;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="podcastsList")
     * @Groups("podcast")
     */
    private $tagsList;

    /**
     * @ORM\OneToMany(targetEntity=PodcastComment::class, mappedBy="PodcastId")
     * @Groups("podcast")
     */
    private $CommentList;
    /**
     * @ORM\OneToMany(targetEntity=PodcastReview::class, mappedBy="PodcastId")
     * @Groups("podcast")
     */
    private $ReviewList;



    public function __construct()
    {
        $this->ReclamationList = new ArrayCollection();
        $this->CommentList = new ArrayCollection();
        $this->PodcastDate=new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPodcastName(): ?string
    {
        return $this->PodcastName;
    }

    public function setPodcastName(string $PodcastName): self
    {
        $this->PodcastName = $PodcastName;

        return $this;
    }

    public function getCurrentlyLive(): ?int
    {
        return $this->currentlyLive;
    }

    public function setCurrentlyLive($currentlyLive): self
    {
        $this->currentlyLive = $currentlyLive;

        return $this;
    }

    public function getIsBlocked(): ?int
    {
        return $this->isBlocked;
    }

    public function setIsBlocked($isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }


    public function getPodcastDescription(): ?string
    {
        return $this->PodcastDescription;
    }

    public function setPodcastDescription(?string $PodcastDescription): self
    {
        $this->PodcastDescription = $PodcastDescription;

        return $this;
    }

    public function getPodcastImage()
    {
        return $this->PodcastImage;
    }

    public function setPodcastImage($PodcastImage): self
    {
        $this->PodcastImage = $PodcastImage;

        return $this;
    }
//
//    public function getPodcastGenre(): ?string
//    {
//        return $this->PodcastGenre;
//    }

//    public function setPodcastGenre(string $PodcastGenre): self
//    {
//        $this->PodcastGenre = $PodcastGenre;
//
//        return $this;
//    }

    public function getPodcastViews(): ?int
    {
        return $this->PodcastViews;
    }

    public function setPodcastViews(int $PodcastViews): self
    {
        $this->PodcastViews = $PodcastViews;

        return $this;
    }

    public function getCurrentlyWatching(): ?int
    {
        return $this->currentlyWatching;
    }

    public function setCurrentlyWatching(int $currentlyWatching): self
    {
        $this->currentlyWatching = $currentlyWatching;

        return $this;
    }

    public function getTags() : ?string
    {
        return $this->tags;
    }

    public function setTags( string $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    public function getCommentsAllowed(): ?int
    {
        return $this->commentsAllowed;
    }

    public function setCommentsAllowed(int $commentsAllowed): self
    {
        $this->commentsAllowed = $commentsAllowed;

        return $this;
    }

    public function getPodcastDate(): ?\DateTimeInterface
    {
        return $this->PodcastDate;
    }

    public function setPodcastDate(\DateTimeInterface $PodcastDate): self
    {
        $this->PodcastDate = $PodcastDate;

        return $this;
    }

    public function getPodcastSource()
    {
        return $this->PodcastSource;
    }

    public function setPodcastSource($PodcastSource): self
    {
        $this->PodcastSource = $PodcastSource;

        return $this;
    }

    public function getPlaylistId(): ?Playlist
    {
        return $this->PlaylistId;
    }

    public function setPlaylistId(?Playlist $PlaylistId): self
    {
        $this->PlaylistId = $PlaylistId;

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
            $reclamationList->setPodcastId($this);
        }

        return $this;
    }

    public function removeReclamationList(Reclamation $reclamationList): self
    {
        if ($this->ReclamationList->removeElement($reclamationList)) {
            // set the owning side to null (unless already changed)
            if ($reclamationList->getPodcastId() === $this) {
                $reclamationList->setPodcastId(null);
            }
        }

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
            $commentList->setPodcastId($this);
        }

        return $this;
    }
    public function removeCommentList(PodcastComment $commentList): self
    {
        if ($this->CommentList->removeElement($commentList)) {
            // set the owning side to null (unless already changed)
            if ($commentList->getPodcastId() === $this) {
                $commentList->setPodcastId(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|PodcastReview[]
     */
    public function getReviewList(): Collection
    {
        return $this->ReviewList;
    }

    public function addReviewList(PodcastReview $reviewList): self
    {
        if (!$this->ReviewList->contains($reviewList)) {
            $this->ReviewList[] = $reviewList;
            $reviewList->setPodcastId($this);
        }

        return $this;
    }

    public function removeReviewList(PodcastReview $reviewList): self
    {
        if ($this->ReviewList->removeElement($reviewList)) {
            // set the owning side to null (unless already changed)
            if ($reviewList->getPodcastId() === $this) {
                $reviewList->setPodcastId(null);
            }
        }

        return $this;
    }

    public function getImage()
    {
    }

    public function setImage(string $fileName)
    {
    }
    /**
     * @return Collection|User[]
     */
    public function getUsersList(): Collection
    {
        return $this->usersList;
    }

    public function addUsersList(User $usersList): self
    {
        if (!$this->usersList->contains($usersList)) {
            $this->usersList[] = $usersList;
            $usersList->addPodcastsFavorite($this);
        }

        return $this;
    }

    public function removeUsersList(User $usersList): self
    {
        if ($this->usersList->removeElement($usersList)) {
            if ($usersList->getPodcastsFavorite()->contains($this))
                $usersList->removePodcastsFavorite($this);
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTagsList()
    {
        return $this->tagsList;
    }

    public function addTagsList(Tag $tagsList): self
    {
            $this->tagsList[] = $tagsList;
            $tagsList->addPodcastsList($this);

        return $this;
    }

    public function removeTagsList(Tag $tagsList): self
    {
        if ($this->tagsList->removeElement($tagsList)) {
            if ($tagsList->getPodcastsList()->contains($this))
                $tagsList->removePodcastsList($this);
        }

        return $this;
    }

}
