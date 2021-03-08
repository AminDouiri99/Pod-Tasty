<?php

namespace App\Entity;

use App\Repository\UserInfoRepository;
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