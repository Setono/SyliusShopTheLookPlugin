<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Form\Type;

use Sylius\Bundle\CoreBundle\Form\Type\ImageType;

final class LookImageType extends ImageType
{
    public function getBlockPrefix(): string
    {
        return 'setono_sylius_shop_the_look_look_image';
    }
}
