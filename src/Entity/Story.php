<?php

namespace App\Entity;

use App\Repository\StoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StoryRepository::class)
 */
class Story
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
    private $storyImage;

    /**
     * @ORM\ManyToOne(targetEntity=UserInfo::class, inversedBy="stories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity=userInfo::class, inversedBy="viewed")
     */
    private $views;


     /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $privacy;

    public function __construct()
    {
        $this->views = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStoryImage()
    {
        return $this->storyImage;
    }

    public function setStoryImage( $storyImage)
    {
        $this->storyImage = $storyImage;
        if ($storyImage) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getOwner(): ?UserInfo
    {
        return $this->owner;
    }

    public function setOwner(?UserInfo $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|userInfo[]
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(userInfo $view): self
    {
        if (!$this->views->contains($view)) {
            $this->views[] = $view;
        }

        return $this;
    }

    public function removeView(userInfo $view): self
    {
        $this->views->removeElement($view);

        return $this;
    }

    public function getPrivacy(): ?bool
    {
        return $this->privacy;
    }

    public function setPrivacy(?bool $privacy): self
    {
        $this->privacy = $privacy;

        return $this;
    }
}
