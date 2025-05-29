<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Order;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderDetailsRepository")
 * @ORM\Table(name="order_details", indexes={@ORM\Index(name="IDX_845CA2C17C78A4E3", columns={"binded_order_id"})})
 */
class OrderDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderDetails")
     * @ORM\JoinColumn(name="binded_order_id", referencedColumnName="id", nullable=false)
     */
    private $bindedOrder;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBindedOrder(): ?Order
    {
        return $this->bindedOrder;
    }

    public function setBindedOrder(Order $order): self
    {
        $this->bindedOrder = $order;
        return $this;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }
}
