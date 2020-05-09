<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DomaineRepository")
 */
class Domaine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $domaineName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SystemeInformation", mappedBy="domaine")
     */
    private $systemeInformation;

    public function __construct()
    {
        $this->systemeInformation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomaineName(): ?string
    {
        return $this->domaineName;
    }

    public function setDomaineName(string $domaineName): self
    {
        $this->domaineName = $domaineName;

        return $this;
    }

    /**
     * @return Collection|SystemeInformation[]
     */
    public function getSystemeInformation(): Collection
    {
        return $this->systemeInformation;
    }

    public function addSystemeInformation(SystemeInformation $systemeInformation): self
    {
        if (!$this->systemeInformation->contains($systemeInformation)) {
            $this->systemeInformation[] = $systemeInformation;
            $systemeInformation->setDomaine($this);
        }

        return $this;
    }

    public function removeSystemeInformation(SystemeInformation $systemeInformation): self
    {
        if ($this->systemeInformation->contains($systemeInformation)) {
            $this->systemeInformation->removeElement($systemeInformation);
            // set the owning side to null (unless already changed)
            if ($systemeInformation->getDomaine() === $this) {
                $systemeInformation->setDomaine(null);
            }
        }

        return $this;
    }
}
