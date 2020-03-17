<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Fixture\Factory;

use function Safe\sprintf;
use Setono\SyliusShopTheLookPlugin\Factory\LookPartFactoryInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookImageInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookPartInterface;
use Setono\SyliusShopTheLookPlugin\Repository\LookRepositoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

class LookExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /** @var LookRepositoryInterface */
    protected $lookRepository;

    /** @var FactoryInterface */
    protected $lookFactory;

    /** @var LookPartFactoryInterface */
    protected $lookPartFactory;

    /** @var FactoryInterface */
    protected $lookImageFactory;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /** @var RepositoryInterface */
    protected $localeRepository;

    /** @var ImageUploaderInterface */
    protected $imageUploader;

    /** @var FileLocatorInterface */
    protected $fileLocator;

    /** @var \Faker\Generator */
    protected $faker;

    /** @var OptionsResolver */
    protected $optionsResolver;

    /** @var OptionsResolver */
    protected $partOptionsResolver;

    public function __construct(
        LookRepositoryInterface $lookRepository,
        FactoryInterface $lookFactory,
        LookPartFactoryInterface $lookPartFactory,
        FactoryInterface $lookImageFactory,
        ProductRepositoryInterface $productRepository,
        RepositoryInterface $localeRepository,
        ImageUploaderInterface $imageUploader,
        FileLocatorInterface $fileLocator
    ) {
        $this->lookRepository = $lookRepository;
        $this->lookFactory = $lookFactory;
        $this->lookPartFactory = $lookPartFactory;
        $this->lookImageFactory = $lookImageFactory;
        $this->productRepository = $productRepository;
        $this->localeRepository = $localeRepository;
        $this->imageUploader = $imageUploader;
        $this->fileLocator = $fileLocator;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver = new OptionsResolver();
        $this->partOptionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
        $this->configurePartOptions($this->partOptionsResolver);
    }

    public function create(array $options = []): LookInterface
    {
        $options = $this->optionsResolver->resolve($options);

        return $this->createLook($options);
    }

    protected function createLook(array $options): LookInterface
    {
        /** @var LookInterface|null $look */
        $look = $this->lookRepository->findOneBy(['code' => $options['code']]);
        if (null === $look) {
            /** @var LookInterface $look */
            $look = $this->lookFactory->createNew();
        }

        $look->setCode($options['code']);

        // add translation for each defined locales
        foreach ($this->getLocales() as $localeCode) {
            $this->createTranslation($look, $localeCode, $options);
        }

        // create or replace with custom translations
        foreach ($options['translations'] as $localeCode => $translationOptions) {
            $this->createTranslation($look, $localeCode, $translationOptions);
        }

        foreach ($options['images'] as $imageOptions) {
            $this->createImage($look, $imageOptions);
        }

        foreach ($options['parts'] as $partOptions) {
            $this->partOptionsResolver->resolve($partOptions);
            $this->createPart($look, $partOptions);
        }

        return $look;
    }

    protected function createTranslation(LookInterface $look, string $localeCode, array $options = []): void
    {
        $options = $this->optionsResolver->resolve($options);

        $look->setCurrentLocale($localeCode);
        $look->setFallbackLocale($localeCode);

        $look->setName($options['name']);
        $look->setDescription($options['description']);
        $look->setSlug(null !== $options['slug'] ? $options['slug'] : StringInflector::nameToCode($options['name']));
    }

    protected function createImage(LookInterface $look, array $imageOptions): void
    {
        $imagePath = $imageOptions['path'];
        $imageType = $imageOptions['type'] ?? null;

        $imagePath = $this->fileLocator->locate($imagePath);
        if (is_array($imagePath)) {
            $imagePath = $imagePath[array_key_first($imagePath)];
        }
        $uploadedImage = new UploadedFile($imagePath, basename($imagePath));

        /** @var LookImageInterface $image */
        $image = $this->lookImageFactory->createNew();
        $image->setFile($uploadedImage);
        $image->setType($imageType);

        $this->imageUploader->upload($image);

        $look->addImage($image);
    }

    protected function createPart(LookInterface $look, array $partOptions): void
    {
        /** @var LookPartInterface $lookPart */
        $lookPart = $this->lookPartFactory->createForLook($look);
        $lookPart->setName($partOptions['name']);

        // Specific products by code
        if (is_array($partOptions['products'])) {
            foreach ($partOptions['products'] as $productCode) {
                $product = $this->productRepository->findOneByCode($productCode);
                Assert::notNull($product, sprintf('Unable to find product %s', $product));
                $lookPart->addProduct($product);
            }

            return;
        }

        // Random products
        $count = (int) $partOptions['products'];
        $this->addRandomProductsToPart($lookPart, $count);
    }

    protected function addRandomProductsToPart(LookPartInterface $lookPart, int $count): void
    {
        $products = $this->productRepository->findAll();

        if (0 === count($products)) {
            return;
        }

        do {
            $product = $this->faker->randomElement($products);
            $lookPart->addProduct($product);
        } while (--$count > 0);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('name', function (Options $options): string {
                /** @var string $name */
                $name = $this->faker->words(3, true);
                return $name;
            })

            ->setDefault('code', function (Options $options): string {
                return StringInflector::nameToCode($options['name']);
            })

            ->setDefault('slug', null)

            ->setDefault('description', function (Options $options): string {
                return $this->faker->paragraph;
            })

            ->setDefault('translations', [])
            ->setAllowedTypes('translations', ['array'])

            ->setDefault('images', [])
            ->setAllowedTypes('images', ['array'])

            ->setDefault('parts', [])
            ->setAllowedTypes('parts', ['array'])
        ;
    }

    protected function configurePartOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('name', function (Options $options): string {
                /** @var string $name */
                $name = $this->faker->words(3, true);
                return $name;
            })

            ->setDefault('products', 3)
            ->setAllowedTypes('products', ['int', 'array'])
        ;
    }

    private function getLocales(): iterable
    {
        /** @var LocaleInterface[] $locales */
        $locales = $this->localeRepository->findAll();
        foreach ($locales as $locale) {
            yield $locale->getCode();
        }
    }
}
