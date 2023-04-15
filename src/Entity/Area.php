<?php

namespace App\Entity;

use App\Repository\AreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AreaRepository::class)]
class Area
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Areaname = null;

    #[ORM\OneToMany(mappedBy: 'area', targetEntity: Destination::class)]
    private Collection $Destinations;

    public function __construct()
    {
        $this->Destinations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAreaname(): ?string
    {
        return $this->Areaname;
    }

    public function setAreaname(string $Areaname): self
    {
        $this->Areaname = $Areaname;

        return $this;
    }

    /**
     * @return Collection<int, Destination>
     */
    public function getDestinations(): Collection
    {
        return $this->Destinations;
    }

    public function addDestination(Destination $destination): self
    {
        if (!$this->Destinations->contains($destination)) {
            $this->Destinations->add($destination);
            $destination->setArea($this);
        }

        return $this;
    }

    public function removeDestination(Destination $destination): self
    {
        if ($this->Destinations->removeElement($destination)) {
            // set the owning side to null (unless already changed)
            if ($destination->getArea() === $this) {
                $destination->setArea(null);
            }
        }

        return $this;
    }
}
