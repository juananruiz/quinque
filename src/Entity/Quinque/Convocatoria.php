<?php

namespace App\Entity\Quinque;

use App\Repository\Quinque\ConvocatoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConvocatoriaRepository::class)]
#[ORM\Table(name: 'quinque_convocatoria')]
class Convocatoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $activa = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaInicioSolicitud = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaFinSolicitud = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaResolucion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $delegaFirmaFecha = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $delegaFirmaPersona = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $delegaFirmaCargo = null;

    #[ORM\OneToMany(targetEntity: Solicitud::class, mappedBy: 'convocatoria')]
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

    public function getActiva(): ?int
    {
        return $this->activa;
    }

    public function setActiva(int $activa): static
    {
        $this->activa = $activa;

        return $this;
    }

    public function getFechaInicioSolicitud(): ?\DateTimeInterface
    {
        return $this->fechaInicioSolicitud;
    }

    public function setFechaInicioSolicitud(\DateTimeInterface $fechaInicioSolicitud): static
    {
        $this->fechaInicioSolicitud = $fechaInicioSolicitud;

        return $this;
    }

    public function getFechaFinSolicitud(): ?\DateTimeInterface
    {
        return $this->fechaFinSolicitud;
    }

    public function setFechaFinSolicitud(\DateTimeInterface $fechaFinSolicitud): static
    {
        $this->fechaFinSolicitud = $fechaFinSolicitud;

        return $this;
    }

    public function getFechaResolucion(): ?\DateTimeInterface
    {
        return $this->fechaResolucion;
    }

    public function setFechaResolucion(?\DateTimeInterface $fechaResolucion): static
    {
        $this->fechaResolucion = $fechaResolucion;

        return $this;
    }

    public function getDelegaFirmaFecha(): ?\DateTimeInterface
    {
        return $this->delegaFirmaFecha;
    }

    public function setDelegaFirmaFecha(?\DateTimeInterface $delegaFirmaFecha): static
    {
        $this->delegaFirmaFecha = $delegaFirmaFecha;

        return $this;
    }

    public function getDelegaFirmaPersona(): ?string
    {
        return $this->delegaFirmaPersona;
    }

    public function setDelegaFirmaPersona(?string $delegaFirmaPersona): static
    {
        $this->delegaFirmaPersona = $delegaFirmaPersona;

        return $this;
    }

    public function getDelegaFirmaCargo(): ?string
    {
        return $this->delegaFirmaCargo;
    }

    public function setDelegaFirmaCargo(?string $delegaFirmaCargo): static
    {
        $this->delegaFirmaCargo = $delegaFirmaCargo;

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
            $solicitud->setConvocatoria($this);
        }

        return $this;
    }

    public function removeSolicitud(Solicitud $solicitud): static
    {
        if ($this->solicitudes->removeElement($solicitud)) {
            // set the owning side to null (unless already changed)
            if ($solicitud->getConvocatoria() === $this) {
                $solicitud->setConvocatoria(null);
            }
        }

        return $this;
    }
}
