<?php

namespace App\Entity;

use App\Repository\DestinationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DestinationRepository::class)]
class Destination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Destinations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Area $area = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Desname = null;

    #[ORM\Column(length: 255)]
    private ?string $Memory = null;

    #[ORM\Column(length: 255)]
    private ?string $Address = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArea(): ?Area
    {
        return $this->area;
    }

    public function setArea(?Area $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getDesname(): ?string
    {
        return $this->Desname;
    }

    public function setDesname(?string $Desname): self
    {
        $this->Desname = $Desname;

        return $this;
    }

    public function getMemory(): ?string
    {
        return $this->Memory;
    }

    public function setMemory(string $Memory): self
    {
        $this->Memory = $Memory;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
    public function getImage():?string
    {
        return $this->Image;
    }
    public  function setImage(string $Image ):self
    {
        $this->Image=$Image;
        return $this;
    }
}
