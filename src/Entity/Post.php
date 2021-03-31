<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

   

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     
     */
    private $postImage;

    /**
     * @ORM\ManyToOne(targetEntity=UserInfo::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

  

    public function getPostImage()
    {
        return $this->postImage;
    }

    public function setPostImage( $postImage): self
    {
        $this->postImage = $postImage;

        return $this;
    }

    public function getUser(): ?userInfo
    {
        return $this->user;
    }

    public function setUser(?userInfo $user): self
    {
        $this->user = $user;

        return $this;
    }
}
