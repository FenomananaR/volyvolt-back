<?php

namespace App\Entity;

use App\Repository\ConsomationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsomationRepository::class)]
class Consomation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'consomations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $clientId = null;

    #[ORM\ManyToOne(inversedBy: 'consomations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appareil $appareilId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?float $consomation = null;

    #[ORM\ManyToOne(inversedBy: 'consomations')]
    private ?ConsomationPredit $consomationPredit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientId(): ?Client
    {
        return $this->clientId;
    }

    public function setClientId(?Client $clientId): static
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getAppareilId(): ?Appareil
    {
        return $this->appareilId;
    }

    public function setAppareilId(?Appareil $appareilId): static
    {
        $this->appareilId = $appareilId;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getConsomation(): ?float
    {
        return $this->consomation;
    }

    public function setConsomation(float $consomation): static
    {
        $this->consomation = $consomation;

        return $this;
    }

    public function getConsomationPredit(): ?ConsomationPredit
    {
        return $this->consomationPredit;
    }

    public function setConsomationPredit(?ConsomationPredit $consomationPredit): static
    {
        $this->consomationPredit = $consomationPredit;

        return $this;
    }
}
