<?php
namespace App\Entity\Quinque;

use App\Repository\Quinque\MeritoEstadoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeritoEstadoRepository::class)]
#[ORM\Table(name: 'quinque_merito_estado')]
class MeritoEstado
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(length: 20)]
    private ?string $color = null;

    #[ORM\OneToMany(mappedBy: 'estado', targetEntity: Merito::class)]
    private Collection $meritos;

    public function __construct()
    {
        $this->meritos = new ArrayCollection();
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return Collection<int, Merito>
     */
    public function getMeritos(): Collection
    {
        return $this->meritos;
    }

    public function addMerito(Merito $merito): static
    {
        if (!$this->meritos->contains($merito)) {
            $this->meritos->add($merito);
            $merito->setEstado($this);
        }

        return $this;
    }

    public function removeMerito(Merito $merito): static
    {
        if ($this->meritos->removeElement($merito)) {
            // set the owning side to null (unless already changed)
            if ($merito->getEstado() === $this) {
                $merito->setEstado(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre;
    }
}
