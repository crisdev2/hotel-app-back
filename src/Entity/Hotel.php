<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $direccion = null;

    #[ORM\Column(nullable: true)]
    private ?int $ciudad = null;

    #[ORM\Column(length: 11, nullable: true)]
    private ?string $nit = null;

    #[ORM\Column(nullable: true)]
    private ?int $habitaciones = null;

    #[ORM\OneToMany(mappedBy: 'idHotel', targetEntity: Habitacion::class, orphanRemoval: true)]
    private Collection $idHabitaciones;

    public function __construct()
    {
        $this->idHabitaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getCiudad(): ?int
    {
        return $this->ciudad;
    }

    public function setCiudad(?int $ciudad): self
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getNit(): ?string
    {
        return $this->nit;
    }

    public function setNit(?string $nit): self
    {
        $this->nit = $nit;

        return $this;
    }

    public function getHabitaciones(): ?int
    {
        return $this->habitaciones;
    }

    public function setHabitaciones(?int $habitaciones): self
    {
        $this->habitaciones = $habitaciones;

        return $this;
    }

    /**
     * @return Collection<int, Habitacion>
     */
    public function getIdHabitaciones(): Collection
    {
        return $this->idHabitaciones;
    }

    public function addIdHabitacione(Habitacion $idHabitacione): self
    {
        if (!$this->idHabitaciones->contains($idHabitacione)) {
            $this->idHabitaciones->add($idHabitacione);
            $idHabitacione->setIdHotel($this);
        }

        return $this;
    }

    public function removeIdHabitacione(Habitacion $idHabitacione): self
    {
        if ($this->idHabitaciones->removeElement($idHabitacione)) {
            // set the owning side to null (unless already changed)
            if ($idHabitacione->getIdHotel() === $this) {
                $idHabitacione->setIdHotel(null);
            }
        }

        return $this;
    }
}
