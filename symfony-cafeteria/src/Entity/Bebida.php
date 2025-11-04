<?php

namespace App\Entity;

use App\Repository\BebidaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BebidaRepository::class)]
class Bebida
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo = null;

    #[ORM\Column(length: 255)]
    private ?string $alergenos = null;

    #[ORM\ManyToOne(targetEntity: Cafeteria::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cafeteria $cafeteria = null;

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

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getAlergenos(): ?string
    {
        return $this->alergenos;
    }

    public function setAlergenos(string $alergenos): static
    {
        $this->alergenos = $alergenos;

        return $this;
    }

    public function getCafeteria(): ?Cafeteria
    {
        return $this->cafeteria;
    }

    public function setCafeteria(?Cafeteria $cafeteria): static
    {
        $this->cafeteria = $cafeteria;

        return $this;
    }
}
