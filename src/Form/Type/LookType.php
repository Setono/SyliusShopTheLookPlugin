<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;

final class LookType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => LookTranslationType::class,
                'label' => 'setono_sylius_shop_the_look.form.look.translations',
            ])
            ->add('percentageDiscount', PercentType::class, [
                'scale' => 3,
                'label' => 'setono_sylius_shop_the_look.form.look.percentage_discount',
            ])
            ->add('position', IntegerType::class, [
                'required' => false,
                'label' => 'setono_sylius_shop_the_look.form.look.position',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => LookImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'setono_sylius_shop_the_look.form.look.images',
            ])
            ->add('parts', CollectionType::class, [
                'entry_type' => LookPartType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => 'setono_sylius_shop_the_look.form.look.parts',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'setono_sylius_shop_the_look.form.look.enabled',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'setono_sylius_shop_the_look_look';
    }
}
