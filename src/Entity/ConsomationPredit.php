<?php

namespace App\Entity;

use App\Repository\ConsomationPreditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsomationPreditRepository::class)]
class ConsomationPredit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $consomation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startWeek = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endWeek = null;

    #[ORM\ManyToOne(inversedBy: 'consomationPredits')]
    private ?Client $client = null;

    #[ORM\Column]
    private ?bool $consomationReel = null;

    #[ORM\OneToMany(mappedBy: 'consomationPredit', targetEntity: Consomation::class)]
    private Collection $consomations;

    public function __construct()
    {
        $this->consomations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStartWeek(): ?\DateTimeInterface
    {
        return $this->startWeek;
    }

    public function setStartWeek(\DateTimeInterface $startWeek): static
    {
        $this->startWeek = $startWeek;

        return $this;
    }

    public function getEndWeek(): ?\DateTimeInterface
    {
        return $this->endWeek;
    }

    public function setEndWeek(\DateTimeInterface $endWeek): static
    {
        $this->endWeek = $endWeek;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function isConsomationReel(): ?bool
    {
        return $this->consomationReel;
    }

    public function setConsomationReel(bool $consomationReel): static
    {
        $this->consomationReel = $consomationReel;

        return $this;
    }

    /**
     * @return Collection<int, Consomation>
     */
    public function getConsomations(): Collection
    {
        return $this->consomations;
    }

    public function addConsomation(Consomation $consomation): static
    {
        if (!$this->consomations->contains($consomation)) {
            $this->consomations->add($consomation);
            $consomation->setConsomationPredit($this);
        }

        return $this;
    }

    public function removeConsomation(Consomation $consomation): static
    {
        if ($this->consomations->removeElement($consomation)) {
            // set the owning side to null (unless already changed)
            if ($consomation->getConsomationPredit() === $this) {
                $consomation->setConsomationPredit(null);
            }
        }

        return $this;
    }
}
