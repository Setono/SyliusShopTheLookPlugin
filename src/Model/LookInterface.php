<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ImagesAwareInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface LookInterface extends ResourceInterface, SlugAwareInterface, TimestampableInterface, ImagesAwareInterface
{
    public function getName(): ?string;

    public function setName(string $name): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    /**
     * @return Collection|ProductInterface[]
     *
     * @psalm-return Collection<array-key, ProductInterface>
     */
    public function getProducts(): Collection;
}
