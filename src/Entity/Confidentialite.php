<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfidentialiteRepository")
 */
class Confidentialite
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
    private $confidentialiteName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SystemeInformation", mappedBy="confidentialite")
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

    public function getConfidentialiteName(): ?string
    {
        return $this->confidentialiteName;
    }

    public function setConfidentialiteName(string $confidentialiteName): self
    {
        $this->confidentialiteName = $confidentialiteName;

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
            $systemeInformation->setConfidentialite($this);
        }

        return $this;
    }

    public function removeSystemeInformation(SystemeInformation $systemeInformation): self
    {
        if ($this->systemeInformation->contains($systemeInformation)) {
            $this->systemeInformation->removeElement($systemeInformation);
            // set the owning side to null (unless already changed)
            if ($systemeInformation->getConfidentialite() === $this) {
                $systemeInformation->setConfidentialite(null);
            }
        }

        return $this;
    }
}
