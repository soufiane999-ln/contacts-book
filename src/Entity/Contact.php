<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;



#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['get'])]
    private ?\DateTimeImmutable $addedAt = null;

    #[ORM\Column(length: 100)]
    #[Groups(['get'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    #[Groups(['get'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 90)]
    #[Groups(['get'])]
    private ?string $street = null;

    #[ORM\Column(length: 5)]
    #[Groups(['get'])]
    private ?int $zip = null;

    #[ORM\Column(length: 80)]
    #[Groups(['get'])]
    private ?string $city = null;

    #[ORM\Column(length: 80)]
    #[Groups(['get'])]
    private ?string $country = null;

    #[ORM\Column(length: 30)]
    #[Groups(['get'])]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: 'date')]
    #[Groups(['get'])]
    private ?\DateTime $birthday = null;

    #[ORM\Column(length: 60)]
    #[Groups(['get'])]
    private ?string $email = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['get'])]
    private ?string $picture = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $contactOf = null;

    /**
     * Contact constructor.
     */
    public function __construct()
    {
        $this->addedAt = new \DateTimeImmutable();
    }

    public static function create( Customer $customer, string $firstName, string $lastName, string $street, string $zip, string $city, string $country, string $phoneNumber, \DateTime $birthday,  string $email, string $picture = null): self
    {
        $contact = new self();
        $contact->setFirstName($firstName)
                ->setLastName($lastName)
                ->setStreet($street)
                ->setZip($zip)
                ->setCity($city)
                ->setCountry($country)
                ->setPhoneNumber($phoneNumber)
                ->setBirthday($birthday)
                ->setEmail($email)
                ->setPicture($picture)
                ->setContactOf($customer);
        return $contact;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->addedAt;
    }

    /**
     * @param \DateTimeImmutable|null $addedAt
     */
    public function setAddedAt(?\DateTimeImmutable $addedAt): self
    {
        $this->addedAt = $addedAt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getZip(): ?int
    {
        return $this->zip;
    }

    /**
     * @param int|null $zip
     */
    public function setZip(?int $zip): self
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     */
    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime|null $birthday
     */
    public function setBirthday(?\DateTime $birthday): self
    {
        $this->birthday = $birthday;
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
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @param string|null $picture
     */
    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;
        return $this;
    }

    public function getContactOf(): ?Customer
    {
        return $this->contactOf;
    }

    public function setContactOf(?Customer $contactOf): self
    {
        $this->contactOf = $contactOf;
        return $this;
    }
}
