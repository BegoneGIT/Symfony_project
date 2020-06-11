<?php

namespace App\Entity;

use App\Repository\LabelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LabelRepository::class)
 */
class Label
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     *
     * @var \App\Entity\User
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="labels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Wallet::class, mappedBy="label")
     */
    private $wallet;

    public function __construct()
    {
        $this->wallet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Wallet[]
     */
    public function getWallet(): Collection
    {
        return $this->wallet;
    }

    public function addWallet(Wallet $wallet): self
    {
        if (!$this->wallet->contains($wallet)) {
            $this->wallet[] = $wallet;
            $wallet->setLabel($this);
        }

        return $this;
    }

    public function removeWallet(Wallet $wallet): self
    {
        if ($this->wallet->contains($wallet)) {
            $this->wallet->removeElement($wallet);
            // set the owning side to null (unless already changed)
            if ($wallet->getLabel() === $this) {
                $wallet->setLabel(null);
            }
        }

        return $this;
    }
}
