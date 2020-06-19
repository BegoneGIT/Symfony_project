<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * Created at.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \App\Entity\User
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="wallets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Label::class, inversedBy="wallet")
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=PaymentTypes::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentType;

    /**
     * @ORM\ManyToOne(targetEntity=TransactionTypes::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $transactionType;

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }




    /**
     * Getter for Amount.
     */
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * Setter for Amount.
     *
     * @return string|null
     */
    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Getter for Created At.
     *
     * @return \DateTimeInterface|null Created at
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for Created at.
     *
     * @param \DateTimeInterface $createdAt Created at
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for Author.
     *
     * @return string|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for Author.
     *
     * @return string|null
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getLabel(): ?Label
    {
        return $this->label;
    }

    public function setLabel(?Label $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPaymentType(): ?PaymentTypes
    {
        return $this->paymentType;
    }

    public function setPaymentType(?PaymentTypes $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getTransactionType(): ?TransactionTypes
    {
        return $this->transactionType;
    }

    public function setTransactionType(?TransactionTypes $transactionType): self
    {
        $this->transactionType = $transactionType;

        return $this;
    }
}
