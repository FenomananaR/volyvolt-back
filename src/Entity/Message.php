<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomOrOrganisme = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $communicationMeans = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $volyvoltAware = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $objet = null;

    #[ORM\Column(length: 600)]
    private ?string $message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomOrOrganisme(): ?string
    {
        return $this->NomOrOrganisme;
    }

    public function setNomOrOrganisme(string $NomOrOrganisme): static
    {
        $this->NomOrOrganisme = $NomOrOrganisme;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCommunicationMeans(): ?string
    {
        return $this->communicationMeans;
    }

    public function setCommunicationMeans(?string $communicationMeans): static
    {
        $this->communicationMeans = $communicationMeans;

        return $this;
    }

    public function getVolyvoltAware(): ?string
    {
        return $this->volyvoltAware;
    }

    public function setVolyvoltAware(?string $volyvoltAware): static
    {
        $this->volyvoltAware = $volyvoltAware;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): static
    {
        $this->objet = $objet;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
