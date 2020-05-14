<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\LookMatcher;

use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Sylius\Component\Order\Model\OrderInterface;

interface LookMatcherInterface
{
    public function match(OrderInterface $order, LookInterface $look): ?array;
}
