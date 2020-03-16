<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface;

class LookPart implements LookPartInterface
{
    protected ?int $id = null;

    protected ?string $name = null;

    protected ?LookInterface $look = null;

    /**
     * @var Collection|ProductInterface[]
     *
     * @psalm-var Collection<array-key, ProductInterface>
     */
    protected Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLook(): ?LookInterface
    {
        return $this->look;
    }

    public function setLook(?LookInterface $look): void
    {
        $this->look = $look;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function hasProducts(): bool
    {
        return !$this->products->isEmpty();
    }

    public function hasProduct(ProductInterface $product): bool
    {
        return $this->products->contains($product);
    }

    public function addProduct(ProductInterface $product): void
    {
        if ($this->hasProduct($product)) {
            return;
        }

        $this->products->add($product);
    }

    public function removeProduct(ProductInterface $product): void
    {
        if (!$this->hasProduct($product)) {
            return;
        }

        $this->products->removeElement($product);
    }
}
