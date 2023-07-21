<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Firstname = null;

    #[ORM\Column(length: 50)]
    private ?string $Lastname = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    private ?Adress $id_Adress = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->Firstname;
    }

    public function setFirstname(string $Firstname): static
    {
        $this->Firstname = $Firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->Lastname;
    }

    public function setLastname(string $Lastname): static
    {
        $this->Lastname = $Lastname;

        return $this;
    }

    public function getIdAdress(): ?Adress
    {
        return $this->id_Adress;
    }

    public function setIdAdress(?Adress $id_Adress): static
    {
        $this->id_Adress = $id_Adress;

        return $this;
    }
}
