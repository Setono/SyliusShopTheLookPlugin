<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Controller\Admin;

use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LookGenerateSlugAction
{
    private SlugGeneratorInterface $slugGenerator;

    public function __construct(SlugGeneratorInterface $slugGenerator)
    {
        $this->slugGenerator = $slugGenerator;
    }

    public function __invoke(Request $request): Response
    {
        $name = (string) $request->query->get('name');

        return new JsonResponse([
            'slug' => $this->slugGenerator->generate($name),
        ]);
    }
}
