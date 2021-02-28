<?php

namespace App\Entity;

use App\Repository\UserInfoRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $UserLastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $UserFirstName;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $UserImage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $UserGender;

    /**
     * @ORM\Column(type="datetime")
     */
    private $UserBirthDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $UserBio;


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

}
