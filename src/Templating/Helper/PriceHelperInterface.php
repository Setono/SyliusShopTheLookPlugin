<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Templating\Helper;

use Setono\SyliusShopTheLookPlugin\Model\LookInterface;

interface PriceHelperInterface
{
    public function getPrice(LookInterface $look, array $context): int;

    public function getDiscount(LookInterface $look, array $context): int;

    public function getTotal(LookInterface $look, array $context): int;
}
