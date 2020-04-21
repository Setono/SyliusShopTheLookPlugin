<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface LookPartInterface extends ResourceInterface
{
    public function getName(): ?string;

    public function setName(string $name): void;

    public function getPosition(): ?int;

    public function setPosition(?int $position): void;

    public function getLook(): ?LookInterface;

    public function setLook(?LookInterface $look): void;

    /**
     * @return Collection|ProductInterface[]
     *
     * @psalm-return Collection<array-key, ProductInterface>
     */
    public function getProducts(): Collection;

    public function hasProducts(): bool;

    public function hasProduct(ProductInterface $product): bool;

    public function addProduct(ProductInterface $product): void;

    public function removeProduct(ProductInterface $product): void;
}
