<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SystemeInformationRepository")
 */
class SystemeInformation
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
    private $usualName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siiName;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Confidentialite", inversedBy="systemeInformation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $confidentialite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Domaine", inversedBy="systemeInformation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domaine;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypologyMI", inversedBy="systemeInformation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SystemeInformation", inversedBy="systemeInformation")
     */
    private $SystemeSupport;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SystemeInformation", mappedBy="SystemeSupport")
     */
    private $systemeInformation;

    public function __construct()
    {
        $this->SystemeSupport = new ArrayCollection();
        $this->systemeInformation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsualName(): ?string
    {
        return $this->usualName;
    }

    public function setUsualName(string $usualName): self
    {
        $this->usualName = $usualName;

        return $this;
    }

    public function getSiiName(): ?string
    {
        return $this->siiName;
    }

    public function setSiiName(string $siiName): self
    {
        $this->siiName = $siiName;

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

    public function getConfidentialite(): ?Confidentialite
    {
        return $this->confidentialite;
    }

    public function setConfidentialite(?Confidentialite $confidentialite): self
    {
        $this->confidentialite = $confidentialite;

        return $this;
    }

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?Domaine $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getType(): ?TypologyMI
    {
        return $this->type;
    }

    public function setType(?TypologyMI $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSystemeSupport(): Collection
    {
        return $this->SystemeSupport;
    }

    public function addSystemeSupport(self $systemeSupport): self
    {
        if (!$this->SystemeSupport->contains($systemeSupport)) {
            $this->SystemeSupport[] = $systemeSupport;
        }

        return $this;
    }

    public function removeSystemeSupport(self $systemeSupport): self
    {
        if ($this->SystemeSupport->contains($systemeSupport)) {
            $this->SystemeSupport->removeElement($systemeSupport);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSystemeInformation(): Collection
    {
        return $this->systemeInformation;
    }

    public function addSystemeInformation(self $systemeInformation): self
    {
        if (!$this->systemeInformation->contains($systemeInformation)) {
            $this->systemeInformation[] = $systemeInformation;
            $systemeInformation->addSystemeSupport($this);
        }

        return $this;
    }

    public function removeSystemeInformation(self $systemeInformation): self
    {
        if ($this->systemeInformation->contains($systemeInformation)) {
            $this->systemeInformation->removeElement($systemeInformation);
            $systemeInformation->removeSystemeSupport($this);
        }

        return $this;
    }
}
