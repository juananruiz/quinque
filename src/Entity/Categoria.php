<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
class Categoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 6)]
    private ?string $abreviatura = null;

    #[ORM\OneToMany(mappedBy: 'categoria', targetEntity: Persona::class)]
    private Collection $personas;

    public function __construct()
    {
        $this->personas = new ArrayCollection();
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

	public function getAbreviatura(): ?string
    {
        return $this->abreviatura;
    }

	public function setAbreviatura(string $abreviatura): static
	{
		$this->abreviatura = $abreviatura;
		return $this;

	}
}