<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Doctrine\ORM;

use Setono\SyliusShopTheLookPlugin\Repository\LookImageRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class LookImageRepository extends EntityRepository implements LookImageRepositoryInterface
{
}
