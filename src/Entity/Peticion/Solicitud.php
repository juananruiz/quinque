<?php

namespace App\Entity\Peticion;

use App\Repository\Peticion\SolicitudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Persona;
use App\Entity\Unidad;
use App\Entity\Peticion\Estado;

#[ORM\Entity(repositoryClass: SolicitudRepository::class)]
#[ORM\Table(name: 'peticion_solicitud')]
#[ORM\HasLifecycleCallbacks]
class Solicitud
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $asunto = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenido = null;

    #[ORM\OneToMany(mappedBy: 'solicitud', targetEntity: Comentario::class, orphanRemoval: true)]
    private Collection $comentarios;

    #[ORM\ManyToOne(targetEntity: Persona::class)]
    #[ORM\JoinColumn(name: 'id_solicitante', nullable: false)]
    private ?Persona $solicitante = null;

    #[ORM\ManyToOne(targetEntity: Persona::class)]
    #[ORM\JoinColumn(name: 'id_asignado', nullable: true)]
    private ?Persona $asignado = null;

    #[ORM\ManyToOne(targetEntity: Unidad::class)]
    #[ORM\JoinColumn(name: 'id_unidad', nullable: true)]
    private ?Unidad $unidad = null;

    #[ORM\ManyToOne(targetEntity: Estado::class)]
    #[ORM\JoinColumn(name: 'id_estado', nullable: false)]
    private ?Estado $estado = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaEntrada = null;

    public function __construct()
    {
        $this->comentarios = new ArrayCollection();
        $this->fechaEntrada = new \DateTime();
    }

    #[ORM\PrePersist]
    public function setFechaEntradaValue()
    {
        $this->fechaEntrada = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAsunto(): ?string
    {
        return $this->asunto;
    }

    public function setAsunto(string $asunto): static
    {
        $this->asunto = $asunto;
        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): static
    {
        $this->contenido = $contenido;
        return $this;
    }

    /**
     * @return Collection<int, Comentario>
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): static
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios->add($comentario);
            $comentario->setSolicitud($this);
        }
        return $this;
    }

    public function removeComentario(Comentario $comentario): static
    {
        if ($this->comentarios->removeElement($comentario)) {
            if ($comentario->getSolicitud() === $this) {
                $comentario->setSolicitud(null);
            }
        }
        return $this;
    }

    public function getSolicitante(): ?Persona
    {
        return $this->solicitante;
    }

    public function setSolicitante(?Persona $solicitante): static
    {
        $this->solicitante = $solicitante;
        return $this;
    }

    public function getAsignado(): ?Persona
    {
        return $this->asignado;
    }

    public function setAsignado(?Persona $asignado): static
    {
        $this->asignado = $asignado;
        return $this;
    }

    public function getUnidad(): ?Unidad
    {
        return $this->unidad;
    }

    public function setUnidad(?Unidad $unidad): static
    {
        $this->unidad = $unidad;
        return $this;
    }

    public function getEstado(): ?Estado
    {
        return $this->estado;
    }

    public function setEstado(?Estado $estado): static
    {
        $this->estado = $estado;
        return $this;
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
}
