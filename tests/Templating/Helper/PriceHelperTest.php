<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusShopTheLookPlugin;

use PHPUnit\Framework\TestCase;
use Setono\SyliusShopTheLookPlugin\Calculator\LookPriceCalculatorInterface;
use Setono\SyliusShopTheLookPlugin\Model\Look;
use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Setono\SyliusShopTheLookPlugin\Templating\Helper\PriceHelper;

final class PriceHelperTest extends TestCase
{
    /**
     * @test
     * @dataProvider priceHelperDataProvider
     */
    public function it_calculates_correctly(float $lookDiscount, int $calculatedTotal, int $calculatedPrice, int $calculatedDiscount): void
    {
        $calculator = new class($calculatedTotal) implements LookPriceCalculatorInterface {
            /** @var int */
            private $total;

            public function __construct($total)
            {
                $this->total = $total;
            }

            public function calculate(LookInterface $look, array $context): int
            {
                return $this->total;
            }
        };

        $look = new Look();
        $look->setDiscount($lookDiscount);

        $helper = new PriceHelper($calculator);
        self::assertEquals($calculatedTotal, $helper->getTotal($look, []));
        self::assertEquals($calculatedPrice, $helper->getPrice($look, []));
        self::assertEquals($calculatedDiscount, $helper->getDiscount($look, []));
    }

    public function priceHelperDataProvider(): array
    {
        return [
            [0.0, 18781, 18781, 0    ],
            [0.1, 18781, 16903, 1878 ],
            [1.0, 18781,     0, 18781],
        ];
    }
}
