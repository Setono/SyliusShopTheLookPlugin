<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Setono\SyliusShopTheLookPlugin\Repository\LookRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class LookRepository extends EntityRepository implements LookRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }
}
