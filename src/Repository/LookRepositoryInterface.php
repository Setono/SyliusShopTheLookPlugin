<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface LookRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;
}
