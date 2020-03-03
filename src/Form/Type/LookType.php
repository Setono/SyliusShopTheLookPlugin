<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class LookType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'setono_sylius_shop_the_look.form.look.name',
            ])
            ->add('slug', TextType::class, [
                'label' => 'setono_sylius_shop_the_look.form.look.slug',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'setono_sylius_shop_the_look.form.look.description',
            ])
            ->add('products', TextareaType::class, [
                'label' => 'setono_sylius_shop_the_look.form.look.products',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => LookImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'setono_sylius_shop_the_look.form.look.images',
                'block_name' => 'entry',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'setono_sylius_shop_the_look_look';
    }
}
