<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Sylius\Component\Core\Model\AdjustmentInterface as BaseAdjustmentInterface;

interface AdjustmentInterface extends BaseAdjustmentInterface
{
    public const ORDER_UNIT_LOOK_ADJUSTMENT = 'order_unit_look';
}
