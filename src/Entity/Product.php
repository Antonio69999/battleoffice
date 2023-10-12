<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToMany(targetEntity: Order::class, inversedBy: 'products')]
    private Collection $product;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $isMainstream = null;

    #[ORM\Column(length: 255)]
    private ?string $gift = null;

    #[ORM\Column(length: 255)]
    private ?string $oldPrice = null;

    #[ORM\Column(length: 255)]
    private ?string $saving = null;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Order $product): static
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
        }

        return $this;
    }

    public function removeProduct(Order $product): static
    {
        $this->product->removeElement($product);

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isIsMainstream(): ?bool
    {
        return $this->isMainstream;
    }

    public function setIsMainstream(bool $isMainstream): static
    {
        $this->isMainstream = $isMainstream;

        return $this;
    }

    public function getGift(): ?string
    {
        return $this->gift;
    }

    public function setGift(string $gift): static
    {
        $this->gift = $gift;

        return $this;
    }

    public function getOldPrice(): ?string
    {
        return $this->oldPrice;
    }

    public function setOldPrice(string $oldPrice): static
    {
        $this->oldPrice = $oldPrice;

        return $this;
    }

    public function getSaving(): ?string
    {
        return $this->saving;
    }

    public function setSaving(string $saving): static
    {
        $this->saving = $saving;

        return $this;
    }
}
