<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusShopTheLookPlugin\Calculator;

use PHPUnit\Framework\TestCase;
use Setono\SyliusShopTheLookPlugin\Calculator\LookPriceCalculator;
use Setono\SyliusShopTheLookPlugin\Model\Look;
use Setono\SyliusShopTheLookPlugin\Model\LookPart;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculator;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ChannelPricing;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Product\Resolver\DefaultProductVariantResolver;

final class LookPriceCalculatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function it_calculates_correctly(int $product1Price, int $product2Price, float $lookDiscount, int $calculatedPrice, int $calculatedDiscount, int $calculatedTotal): void
    {
        $calculator = new LookPriceCalculator(
            new DefaultProductVariantResolver(),
            new ProductVariantPriceCalculator()
        );

        $channel = new Channel();
        $channel->setCode('US_WEB');
        $context = ['channel' => $channel];

        $look = new Look();
        $look->setDiscount($lookDiscount);

        // 1
        $channelPricing1 = new ChannelPricing();
        $channelPricing1->setChannelCode('US_WEB');
        $channelPricing1->setPrice($product1Price);

        $productVariant1 = new ProductVariant();
        $productVariant1->addChannelPricing($channelPricing1);

        $product1 = new Product();
        $product1->addVariant($productVariant1);

        $lookPart1 = new LookPart();
        $lookPart1->addProduct($product1);

        // 2
        $channelPricing2 = new ChannelPricing();
        $channelPricing2->setChannelCode('US_WEB');
        $channelPricing2->setPrice($product2Price);

        $productVariant2 = new ProductVariant();
        $productVariant2->addChannelPricing($channelPricing2);

        $product2 = new Product();
        $product2->addVariant($productVariant2);

        $lookPart2 = new LookPart();
        $lookPart2->addProduct($product2);

        $look->addPart($lookPart1);
        $look->addPart($lookPart2);

        self::assertEquals($calculatedPrice, $calculator->calculatePrice($look, $context));
        self::assertEquals($calculatedDiscount, $calculator->calculateDiscount($look, $context));
        self::assertEquals($calculatedTotal, $calculator->calculateTotal($look, $context));
    }

    public function dataProvider(): array
    {
        return [
            [18781, 0, 0.0, 18781, 0,     18781],
            [18781, 0, 0.1, 18781, 1878,  16903],
            [18781, 0, 1.0, 18781, 18781,     0],

            [0, 18781, 0.0, 18781, 0,     18781],
            [0, 18781, 0.1, 18781, 1878,  16903],
            [0, 18781, 1.0, 18781, 18781,     0],

            [781, 18000, 0.0, 18781,     0, 18781],
            [781, 18000, 0.1, 18781,  1878, 16903],
            [781, 18000, 0.05, 18781,  939, 17842],
            [781, 18000, 0.06, 18781, 1127, 17654],
            [781, 18000, 1.0, 18781, 18781,     0],

            // We expect discount 3087 rather than 3086
            // 10288 * .3 = 3086,4 ~ 3086
            // But
            // 7325 * .3 = 2197,5  ~ 2198
            // 2963 * .3 = 888,9   ~  889
            // sum = 3087
            // 10288 - 3087 = 7201
            [7325, 2963, 0.3, 10288, 3087,   7201],

            // We expect discount 7326 rather than 7325, as round(0.5)=1
            [7325, 7325, 0.5, 14650, 7326,   7324],

            // We expect discount 7324 as round(0.4)=0
            [7324, 7324, 0.5, 14648, 7324,   7324],
        ];
    }
}
