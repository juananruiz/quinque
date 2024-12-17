<?php

namespace App\Entity\Peticion;

use App\Repository\Peticion\UnidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnidadRepository::class)]
class Unidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $codigo = null;

    #[ORM\OneToMany(mappedBy: 'unidad', targetEntity: Solicitud::class)]
    private Collection $solicitudes;

    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
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

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): static
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return Collection<int, Solicitud>
     */
    public function getSolicitudes(): Collection
    {
        return $this->solicitudes;
    }

    public function addSolicitud(Solicitud $solicitud): static
    {
        if (!$this->solicitudes->contains($solicitud)) {
            $this->solicitudes->add($solicitud);
            $solicitud->setUnidad($this);
        }

        return $this;
    }

    public function removeSolicitud(Solicitud $solicitud): static
    {
        if ($this->solicitudes->removeElement($solicitud)) {
            // set the owning side to null (unless already changed)
            if ($solicitud->getUnidad() === $this) {
                $solicitud->setUnidad(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre;
    }
}
