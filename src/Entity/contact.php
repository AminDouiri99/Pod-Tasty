<?php
namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\FormTypeInterface;

class contact {

    /**
     * @var string|null
     * @Assert\NotBlank ()
     * @Assert\Length (min=3 max=100)
     */
    private $firstname;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=3 max=100)
     */
    private $lastname;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Regex (
     *     pattern"/[0-9]{8}"
     * )
     */
    private $phone;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email
     */
    private $email;


    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length (min=10)
     */
    private $message;

//    /**
//     * @var Property
//     */
//    private $property;


    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     * @return contact
     */
    public function setFirstname(?string $firstname): contact
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     * @return contact
     */
    public function setLastname(?string $lastname): contact
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return contact
     */
    public function setPhone(?string $phone): contact
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return contact
     */
    public function setEmail(?string $email): contact
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return contact
     */
    public function setMessage(?string $message): contact
    {
        $this->message = $message;
        return $this;
    }

//    /**
//     * @return Property|null
//     */
//    public function getProperty(): ?Property
//    {
//        return $this->property;
//    }
//
//    /**
//     * @param Property|null $property
//     * @return contact
//     */
//    public function setProperty(?Property $property): contact
//    {
//        $this->property = $property;
//        return $this;
//    }



}
