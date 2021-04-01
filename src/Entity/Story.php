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

    public function __construct()
    {
        $this->views = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStoryImage(): ?string
    {
        return $this->storyImage;
    }

    public function setStoryImage(string $storyImage): self
    {
        $this->storyImage = $storyImage;

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
}
