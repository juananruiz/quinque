<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonaRepository::class)]
class Persona
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $apellidos = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaNacimiento = null;

    #[ORM\Column(length: 25)]
    private ?string $dni = null;

    #[ORM\OneToMany(mappedBy: 'persona', targetEntity: QuinquenioSolicitud::class, orphanRemoval: true)]
    private Collection $quinquenioSolicitudes;

    public function __construct()
    {
        $this->quinquenioSolicitudes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(?\DateTimeInterface $fechaNacimiento): static
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * @return Collection<int, QuinquenioSolicitud>
     */
    public function getQuinquenioSolicitudes(): Collection
    {
        return $this->quinquenioSolicitudes;
    }

    public function addQuinquenioSolicitud(QuinquenioSolicitud $quinquenioSolicitud): static
    {
        if (!$this->quinquenioSolicitudes->contains($quinquenioSolicitud)) {
            $this->quinquenioSolicitudes->add($quinquenioSolicitud);
            $quinquenioSolicitud->setPersona($this);
        }

        return $this;
    }

    public function removeQuinquenioSolicitud(QuinquenioSolicitud $quinquenioSolicitud): static
    {
        if ($this->quinquenioSolicitudes->removeElement($quinquenioSolicitud)) {
            // set the owning side to null (unless already changed)
            if ($quinquenioSolicitud->getPersona() === $this) {
                $quinquenioSolicitud->setPersona(null);
            }
        }

        return $this;
    }
}
