<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypologyMIRepository")
 */
class TypologyMI
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
    private $shortName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $longName;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SystemeInformation", mappedBy="type")
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

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getLongName(): ?string
    {
        return $this->longName;
    }

    public function setLongName(string $longName): self
    {
        $this->longName = $longName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $systemeInformation->setType($this);
        }

        return $this;
    }

    public function removeSystemeInformation(SystemeInformation $systemeInformation): self
    {
        if ($this->systemeInformation->contains($systemeInformation)) {
            $this->systemeInformation->removeElement($systemeInformation);
            // set the owning side to null (unless already changed)
            if ($systemeInformation->getType() === $this) {
                $systemeInformation->setType(null);
            }
        }

        return $this;
    }
}
