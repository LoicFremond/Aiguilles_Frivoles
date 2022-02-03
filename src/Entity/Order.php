<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
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
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="orders")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     */
    private $client;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $Created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="orders")
     */
    private $status;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->product->removeElement($product);

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->Created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $Created_at): self
    {
        $this->Created_at = $Created_at;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

}
