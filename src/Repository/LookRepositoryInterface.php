<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface LookRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;

    public function createShopListQueryBuilder(string $locale): QueryBuilder;

    public function findEnabled(): array;

    public function findLatest(string $locale, int $count): array;

    public function findRelatedToProduct(ProductInterface $product, string $locale): array;

    public function findOneBySlug(string $locale, string $slug): ?LookInterface;
}
