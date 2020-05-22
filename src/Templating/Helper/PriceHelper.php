<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Templating\Helper;

use Setono\SyliusShopTheLookPlugin\Calculator\LookPriceCalculatorInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Symfony\Component\Templating\Helper\Helper;

class PriceHelper extends Helper
{
    /** @var LookPriceCalculatorInterface */
    private $lookPriceCalculator;

    public function __construct(LookPriceCalculatorInterface $lookPriceCalculator)
    {
        $this->lookPriceCalculator = $lookPriceCalculator;
    }

    public function getPrice(LookInterface $look, array $context): int
    {
        return (int) round($this->getTotal($look, $context) * (1 - $look->getDiscount()));
    }

    public function getDiscount(LookInterface $look, array $context): int
    {
        return (int) round($this->getTotal($look, $context) * $look->getDiscount());
    }

    public function getTotal(LookInterface $look, array $context): int
    {
        return $this->lookPriceCalculator->calculate($look, $context);
    }

    public function getName(): string
    {
        return 'setono_look_calculate_price';
    }
}
