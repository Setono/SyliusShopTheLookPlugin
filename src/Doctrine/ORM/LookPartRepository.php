<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Doctrine\ORM;

use Setono\SyliusShopTheLookPlugin\Repository\LookPartRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class LookPartRepository extends EntityRepository implements LookPartRepositoryInterface
{
}
