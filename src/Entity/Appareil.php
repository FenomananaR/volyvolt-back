<?php

namespace App\Entity;

use App\Repository\AppareilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppareilRepository::class)]
class Appareil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $appareilId = null;

    #[ORM\OneToMany(mappedBy: 'appareilId', targetEntity: Consomation::class)]
    private Collection $consomations;

    #[ORM\ManyToOne(inversedBy: 'appareils')]
    private ?Client $client = null;

    public function __construct()
    {
        $this->consomations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppareilId(): ?string
    {
        return $this->appareilId;
    }

    public function setAppareilId(string $appareilId): static
    {
        $this->appareilId = $appareilId;

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
            $consomation->setAppareilId($this);
        }

        return $this;
    }

    public function removeConsomation(Consomation $consomation): static
    {
        if ($this->consomations->removeElement($consomation)) {
            // set the owning side to null (unless already changed)
            if ($consomation->getAppareilId() === $this) {
                $consomation->setAppareilId(null);
            }
        }

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
}
