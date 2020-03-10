<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class LookTranslationType extends AbstractResourceType
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
                'required' => false,
                'label' => 'setono_sylius_shop_the_look.form.look.description',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'setono_sylius_shop_the_look_look_translation';
    }
}
