<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\OrderRepository;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`");
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeSession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paypalOrderId;

    /**
     * @ORM\Column(type="integer")
     */
    private $state = 0;

    /**
     * Total de la commande en cents (produits uniquement)
     *
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderDetails", mappedBy="bindedOrder", cascade={"persist"})
     */
    private $orderDetails;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
        $this->createdAt    = new \DateTime();
        $this->reference    = $this->createdAt->format('YmdHis').'-'.uniqid();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?\App\Entity\User
    {
        return $this->user;
    }

    public function setUser(\App\Entity\User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getStripeSession(): ?string
    {
        return $this->stripeSession;
    }

    public function setStripeSession(?string $stripeSession): self
    {
        $this->stripeSession = $stripeSession;

        return $this;
    }

    public function getPaypalOrderId(): ?string
    {
        return $this->paypalOrderId;
    }

    public function setPaypalOrderId(?string $paypalOrderId): self
    {
        $this->paypalOrderId = $paypalOrderId;

        return $this;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|OrderDetails[]
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    /**
     * Retourne le nombre total d’articles dans la commande
     */
    public function getTotalQuantity(): int
    {
        $total = 0;
        foreach ($this->orderDetails as $detail) {
            $total += $detail->getQuantity();
        }
        return $total;
    }

    public function addOrderDetail(OrderDetails $detail): self
    {
        if (!$this->orderDetails->contains($detail)) {
            $detail->setBindedOrder($this);
            $this->orderDetails->add($detail);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $detail): self
    {
        if ($this->orderDetails->contains($detail)) {
            $this->orderDetails->removeElement($detail);
            // set the owning side to null (unless already changed)
            if ($detail->getBindedOrder() === $this) {
                $detail->setBindedOrder(null);
            }
        }

        return $this;
    }
}
