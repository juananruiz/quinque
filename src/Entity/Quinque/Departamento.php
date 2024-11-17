<?php

namespace App\Entity\Quinque;

use App\Repository\Quinque\DepartamentoRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartamentoRepository::class)]
#[ORM\Table(name: 'quinque_departamento')]
class Departamento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Persona::class, mappedBy: 'departamento')]
    private Collection $personas;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $codigoRpt = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $codigoUxxi = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigoRpt(): ?string
    {
        return $this->codigoRpt;
    }

    public function setCodigoRpt(?string $codigoRpt): static
    {
        $this->codigoRpt = $codigoRpt;

        return $this;
    }

    public function getCodigoUxxi(): ?string
    {
        return $this->codigoUxxi;
    }

    public function setCodigoUxxi(?string $codigoUxxi): static
    {
        $this->codigoUxxi = $codigoUxxi;

        return $this;
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
}
