<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tagStyle;


    /**
     * @ORM\ManyToMany (targetEntity=Podcast::class, inversedBy="tagsList")
     */
    private $podcastsList;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function getTagStyle(): ?string
    {
        return $this->tagStyle;
    }

    public function setTagStyle(string $tagStyle): self
    {
        $this->tagStyle = $tagStyle;

        return $this;
    }

    /**
     * @return Collection|Podcast[]
     */
    public function getPodcastsList()
    {
        return $this->podcastsList;
    }
    public function addPodcastsList(Podcast $podcastsList): self
    {
        if (!$this->podcastsList->contains($podcastsList)) {
            $this->podcastsList[] = $podcastsList;
            $podcastsList->addTagsList($this);
        }

        return $this;
    }

    public function removePodcastsList(Podcast $podcastsList): self
    {
        if ($this->podcastsList->removeElement($podcastsList)) {
            if ($podcastsList->getTagsList()->contains($this))
                $podcastsList->removeTagsList($this);
        }

        return $this;
    }


}
