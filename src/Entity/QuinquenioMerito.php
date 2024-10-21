<?php

namespace App\Entity;

use App\Repository\QuinquenioMeritoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuinquenioMeritoRepository::class)]
class QuinquenioMerito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $organismo = null;

    #[ORM\Column]
    private ?int $categoriaId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaInicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaFin = null;

    #[ORM\Column]
    private ?int $estado = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: QuinquenioSolicitud::class, inversedBy: 'quinquenioMeritos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QuinquenioSolicitud $quinquenioSolicitud = null;

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

    public function getCategoriaId(): ?int
    {
        return $this->categoriaId;
    }

    public function setCategoriaId(int $categoriaId): static
    {
        $this->categoriaId = $categoriaId;

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

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): static
    {
        $this->estado = $estado;

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

    public function getQuinquenioSolicitud(): ?QuinquenioSolicitud
    {
        return $this->quinquenioSolicitud;
    }

    public function setQuinquenioSolicitud(?QuinquenioSolicitud $quinquenioSolicitud): static
    {
        $this->quinquenioSolicitud = $quinquenioSolicitud;

        return $this;
    }
}
