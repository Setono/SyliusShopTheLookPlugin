<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Twig;

use Setono\SyliusShopTheLookPlugin\Templating\Helper\PriceHelperInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class PriceExtension extends AbstractExtension
{
    private PriceHelperInterface $helper;

    public function __construct(PriceHelperInterface $helper)
    {
        $this->helper = $helper;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('setono_look_calculate_price', [$this->helper, 'getPrice']),
            new TwigFilter('setono_look_calculate_discount', [$this->helper, 'getDiscount']),
            new TwigFilter('setono_look_calculate_total', [$this->helper, 'getTotal']),
        ];
    }
}
