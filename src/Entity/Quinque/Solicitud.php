<?php

namespace App\Entity\Quinque;

use App\Repository\Quinque\SolicitudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SolicitudRepository::class)]
#[ORM\Table(name: 'quinque_solicitud')]
/**
 * Class Solicitud.
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

    #[ORM\ManyToOne(targetEntity: Persona::class, inversedBy: 'solicitudes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $persona = null;

    #[ORM\ManyToOne(inversedBy: 'solicitudes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Convocatoria $convocatoria = null;

    #[ORM\ManyToOne(targetEntity: SolicitudEstado::class, inversedBy: 'solicitudes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?SolicitudEstado $estado = null;

    #[
        ORM\OneToMany(
            targetEntity: Merito::class,
            mappedBy: 'solicitud',
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

    public function getConvocatoria(): ?Convocatoria
    {
        return $this->convocatoria;
    }

    public function setConvocatoria(?Convocatoria $convocatoria): static
    {
        $this->convocatoria = $convocatoria;

        return $this;
    }

    public function getEstado(): ?SolicitudEstado
    {
        return $this->estado;
    }

    public function setEstado(?SolicitudEstado $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Calcula la suma de los días transcurridos en cada mérito cuyo estado sea 1.
     *
     * @return int suma total de días
     */
    public function getMeritosComputados(): int
    {
        $diasComputados = 0;
        foreach ($this->getMeritos() as $merito) {
            // Suma solo los días de los méritos cuyo estado sea 2 (admitido).
            // TODO: ver si esto funciona
            if (2 === $merito->getEstado()?->getId()) {
                $dias = $merito->getDiasTranscurridos();
                if (null !== $dias) {
                    $diasComputados += $dias;
                }
            }
        }

        return $diasComputados;
    }
}
