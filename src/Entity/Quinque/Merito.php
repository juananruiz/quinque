<?php

namespace App\Entity\Quinque;

use App\Repository\Quinque\MeritoRepository;
use App\Entity\Quinque\MeritoEstado;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeritoRepository::class)]
#[ORM\Table(name: 'quinque_merito')]
class Merito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $organismo = null;

    #[ORM\ManyToOne(targetEntity: Categoria::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categoria $categoria = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaInicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaFin = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $dedicacion = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observaciones = null;

    #[ORM\ManyToOne(inversedBy: 'meritos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MeritoEstado $estado = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Solicitud::class, inversedBy: 'meritos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Solicitud $solicitud = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrganismo(): ?string
    {
        return $this->organismo;
    }

    public function setOrganismo(string $organismo): static
    {
        $this->organismo = $organismo;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getDedicacion(): ?int
    {
        return $this->dedicacion;
    }

    public function setDedicacion(int $dedicacion): static
    {
        $this->dedicacion = $dedicacion;

        return $this;
    }

    public function getEstado(): ?MeritoEstado
    {
        return $this->estado;
    }

    public function setEstado(?MeritoEstado $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fechaInicio): static
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTimeInterface $fechaFin): static
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): static
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getSolicitud(): ?Solicitud
    {
        return $this->solicitud;
    }

    public function setSolicitud(?Solicitud $solicitud): static
    {
        $this->solicitud = $solicitud;

        return $this;
    }

    /**
     * Calculate the number of days between fechaInicio and fechaFin (ambas inclusive).
     */
    public function getDiasTranscurridos(): ?int
    {
        if (!$this->fechaInicio || !$this->fechaFin) {
            return null;
        }

        $diff = $this->fechaFin->diff($this->fechaInicio);

        // Add 1 to include both start and end dates
        return $diff->days + 1;
    }
}
