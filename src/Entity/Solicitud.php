<?php

namespace App\Entity;

use App\Repository\SolicitudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SolicitudRepository::class)]
/**
 * Class Solicitud
 * 
 * Represents a request entity with associated merits and a person.
 */
class Solicitud
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaEntrada = null;

    #[ORM\Column(type: Types::STRING, length: 25)]
    private ?string $convocatoria = null;

    #[ORM\ManyToOne(targetEntity: Persona::class, inversedBy: 'solicitudes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $persona = null;

    #[
        ORM\OneToMany(
            mappedBy: 'solicitud',
            targetEntity: Merito::class,
            orphanRemoval: true
        )
    ]
    private Collection $meritos;


    public function __construct()
    {
        $this->meritos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaEntrada(): ?\DateTimeInterface
    {
        return $this->fechaEntrada;
    }

    public function setFechaEntrada(\DateTimeInterface $fechaEntrada): static
    {
        $this->fechaEntrada = $fechaEntrada;

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): static
    {
        $this->persona = $persona;

        return $this;
    }

    public function getPersonaId(): ?int
    {
        return $this->persona ? $this->persona->getId() : null;
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
            $merito->setSolicitud($this);
        }

        return $this;
    }

    public function removeMerito(Merito $merito): static
    {
        if ($this->meritos->removeElement($merito)) {
            // set the owning side to null (unless already changed)
            if ($merito->getSolicitud() === $this) {
                $merito->setSolicitud(null);
            }
        }

        return $this;
    }

    public function getConvocatoria(): ?string
    {
        return $this->convocatoria;
    }

    public function setConvocatoria(?string $convocatoria): static
    {
        $this->convocatoria = $convocatoria;

        return $this;
    }

    /**
     * Calcula la suma de los días transcurridos en cada mérito cuyo estado sea 1.
     *
     * @return int Suma total de días.
     */
    public function getTotalDiasComputados(): int
    {
        $totalDias = 0;

        foreach ($this->getMeritos() as $merito) {
            if ($merito->getEstado() === 1) {
                $dias = $merito->getDiasTranscurridos();
                if ($dias !== null) {
                    $totalDias += $dias;
                }
            }
        }

        return $totalDias;
    }
}
