<?php

namespace App\Entity;

use App\Repository\QuinquenioSolicitudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuinquenioSolicitudRepository::class)]
class QuinquenioSolicitud
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaEntrada = null;

    #[ORM\ManyToOne(targetEntity: Persona::class, inversedBy: 'quinquenioSolicitudes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $persona = null;

    #[ORM\OneToMany(mappedBy: 'quinquenioSolicitud', targetEntity: QuinquenioMerito::class, orphanRemoval: true)]
    private Collection $quinquenioMeritos;

    public function __construct()
    {
        $this->quinquenioMeritos = new ArrayCollection();
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

    /**
     * @return Collection<int, QuinquenioMerito>
     */
    public function getQuinquenioMeritos(): Collection
    {
        return $this->quinquenioMeritos;
    }

    public function addQuinquenioMerito(QuinquenioMerito $quinquenioMerito): static
    {
        if (!$this->quinquenioMeritos->contains($quinquenioMerito)) {
            $this->quinquenioMeritos->add($quinquenioMerito);
            $quinquenioMerito->setQuinquenioSolicitud($this);
        }

        return $this;
    }

    public function removeQuinquenioMerito(QuinquenioMerito $quinquenioMerito): static
    {
        if ($this->quinquenioMeritos->removeElement($quinquenioMerito)) {
            // set the owning side to null (unless already changed)
            if ($quinquenioMerito->getQuinquenioSolicitud() === $this) {
                $quinquenioMerito->setQuinquenioSolicitud(null);
            }
        }

        return $this;
    }
}
