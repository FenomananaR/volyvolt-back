<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $clientId = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'clientId', targetEntity: Consomation::class)]
    private Collection $consomations;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Appareil::class)]
    private Collection $appareils;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: ConsomationPredit::class)]
    private Collection $consomationPredits;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $usedDevices = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombrePerson = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $consomationTotal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uDevices = null;

    public function __construct()
    {
        $this->consomations = new ArrayCollection();
        $this->appareils = new ArrayCollection();
        $this->consomationPredits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): static
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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
            $consomation->setClientId($this);
        }

        return $this;
    }

    public function removeConsomation(Consomation $consomation): static
    {
        if ($this->consomations->removeElement($consomation)) {
            // set the owning side to null (unless already changed)
            if ($consomation->getClientId() === $this) {
                $consomation->setClientId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appareil>
     */
    public function getAppareils(): Collection
    {
        return $this->appareils;
    }

    public function addAppareil(Appareil $appareil): static
    {
        if (!$this->appareils->contains($appareil)) {
            $this->appareils->add($appareil);
            $appareil->setClient($this);
        }

        return $this;
    }

    public function removeAppareil(Appareil $appareil): static
    {
        if ($this->appareils->removeElement($appareil)) {
            // set the owning side to null (unless already changed)
            if ($appareil->getClient() === $this) {
                $appareil->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ConsomationPredit>
     */
    public function getConsomationPredits(): Collection
    {
        return $this->consomationPredits;
    }

    public function addConsomationPredit(ConsomationPredit $consomationPredit): static
    {
        if (!$this->consomationPredits->contains($consomationPredit)) {
            $this->consomationPredits->add($consomationPredit);
            $consomationPredit->setClient($this);
        }

        return $this;
    }

    public function removeConsomationPredit(ConsomationPredit $consomationPredit): static
    {
        if ($this->consomationPredits->removeElement($consomationPredit)) {
            // set the owning side to null (unless already changed)
            if ($consomationPredit->getClient() === $this) {
                $consomationPredit->setClient(null);
            }
        }

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getUsedDevices(): ?array
    {
        return $this->usedDevices;
    }

    public function setUsedDevices(?array $usedDevices): static
    {
        $this->usedDevices = $usedDevices;

        return $this;
    }

    public function getNombrePerson(): ?string
    {
        return $this->nombrePerson;
    }

    public function setNombrePerson(?string $nombrePerson): static
    {
        $this->nombrePerson = $nombrePerson;

        return $this;
    }

    public function getConsomationTotal(): ?string
    {
        return $this->consomationTotal;
    }

    public function setConsomationTotal(?string $consomationTotal): static
    {
        $this->consomationTotal = $consomationTotal;

        return $this;
    }

    public function getUDevices(): ?string
    {
        return $this->uDevices;
    }

    public function setUDevices(?string $uDevices): static
    {
        $this->uDevices = $uDevices;

        return $this;
    }
}
