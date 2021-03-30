<?php

namespace App\Entity;

use App\Repository\UserInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserInfoRepository::class)
 */
class UserInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your LastName!")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your name cannot contain a number"
     * )
     * @Assert\Regex("/^([^0-9]*)$/")
     */
    private $UserLastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your FitstName!")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your name cannot contain a number"
     * )     */
    private $UserFirstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $UserImage;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your Gender!")
     */
    private $UserGender;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Please enter your BirthDate!")
     */
    private $UserBirthDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $UserBio;

    /**
     * @ORM\ManyToMany(targetEntity=UserInfo::class, inversedBy="Following")
     */
    private $Followers;

    /**
     * @ORM\ManyToMany(targetEntity=UserInfo::class, mappedBy="Followers")
     */
    private $Following;

    public function __construct()
    {
        $this->Followers = new ArrayCollection();
        $this->Following = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserLastName(): ?string
    {
        return $this->UserLastName;
    }

    public function setUserLastName(string $UserLastName): self
    {
        $this->UserLastName = $UserLastName;

        return $this;
    }

    public function getUserFirstName(): ?string
    {
        return $this->UserFirstName;
    }

    public function setUserFirstName(string $UserFirstName): self
    {
        $this->UserFirstName = $UserFirstName;

        return $this;
    }

    public function getUserImage()
    {
        return $this->UserImage;
    }

    public function setUserImage($UserImage): self
    {
        $this->UserImage = $UserImage;

        return $this;
    }

    public function getUserGender(): ?string
    {
        return $this->UserGender;
    }

    public function setUserGender(string $UserGender): self
    {
        $this->UserGender = $UserGender;

        return $this;
    }

    public function getUserBirthDate(): ?\DateTimeInterface
    {
        return $this->UserBirthDate;
    }

    public function setUserBirthDate(\DateTimeInterface $UserBirthDate): self
    {
        $this->UserBirthDate = $UserBirthDate;

        return $this;
    }

    public function getUserBio(): ?string
    {
        return $this->UserBio;
    }

    public function setUserBio(?string $UserBio): self
    {
        $this->UserBio = $UserBio;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFollowers(): Collection
    {
        return $this->Followers;
    }

    public function addFollower(self $follower): self
    {
        if (!$this->Followers->contains($follower)) {
            $this->Followers[] = $follower;
        }

        return $this;
    }

    public function removeFollower(self $follower): self
    {
        $this->Followers->removeElement($follower);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFollowing(): Collection
    {
        return $this->Following;
    }

    public function addFollowing(self $following): self
    {
        if (!$this->Following->contains($following)) {
            $this->Following[] = $following;
            $following->addFollower($this);
        }

        return $this;
    }

    public function removeFollowing(self $following): self
    {
        if ($this->Following->removeElement($following)) {
            $following->removeFollower($this);
        }

        return $this;
    }


}