<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Fixture\Factory;

use Faker\Factory;
use Faker\Generator;
use function Safe\sprintf;
use Setono\SyliusShopTheLookPlugin\Factory\LookPartFactoryInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookImageInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookPartInterface;
use Setono\SyliusShopTheLookPlugin\Repository\LookRepositoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
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

class LookExampleFactory extends AbstractExampleFactory
{
    protected LookRepositoryInterface $lookRepository;

    protected FactoryInterface $lookFactory;

    protected LookPartFactoryInterface $lookPartFactory;

    protected FactoryInterface $lookImageFactory;

    protected ProductRepositoryInterface $productRepository;

    protected RepositoryInterface $localeRepository;

    protected ImageUploaderInterface $imageUploader;

    protected FileLocatorInterface $fileLocator;

    protected Generator $faker;

    protected OptionsResolver $optionsResolver;

    protected OptionsResolver $partOptionsResolver;

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

        $this->faker = Factory::create();
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
        $look->setPercentageDiscount($options['percentage_discount']);
        $look->setPosition($options['position']);
        $look->setEnabled($options['enabled']);

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
            $partOptions = $this->partOptionsResolver->resolve($partOptions);
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
        $look->setDescription($options['description'] ?? $this->faker->paragraph);
        $look->setSlug($options['slug'] ?? StringInflector::nameToCode($options['name']));
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
        $lookPart->setPosition($partOptions['position']);

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

            ->setDefault('code', static function (Options $options): string {
                return StringInflector::nameToCode($options['name']);
            })

            ->setDefault('percentage_discount', function (Options $options): float {
                return $this->faker->randomFloat(3, 0, 100);
            })
            ->setNormalizer('percentage_discount', static function (Options $options, $value): float {
                if ($value >= 0 && $value <= 100) {
                    $value = $value / 100;
                }

                Assert::range($value, 0, 1, 'Percentage discount can be set in 0..100 range');

                return $value;
            })
            ->setAllowedTypes('percentage_discount', ['int', 'float'])

            ->setDefault('enabled', true)
            ->setAllowedTypes('enabled', ['bool'])

            ->setDefault('position', 0)
            ->setAllowedTypes('position', ['int'])

            ->setDefault('slug', null)
            ->setDefault('description', null)

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

            ->setDefault('position', 0)
            ->setAllowedTypes('position', ['int'])

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
