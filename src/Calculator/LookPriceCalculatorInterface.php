<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Calculator;

use Setono\SyliusShopTheLookPlugin\Model\LookInterface;

interface LookPriceCalculatorInterface
{
    public function calculate(LookInterface $look, array $context): int;
}
