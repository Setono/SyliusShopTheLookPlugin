<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class LookPartType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'setono_sylius_shop_the_look.form.look_part.name',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'setono_sylius_shop_the_look_look_part';
    }
}
