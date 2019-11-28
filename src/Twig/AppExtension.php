<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Twig;

use App\Util\CategoryCounter;
use App\Util\RegionCounter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Intl\Languages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $parser;
    private $localeCodes;
    private $locales;
    private $container;

    public function __construct(ContainerInterface $container, string $locales)
    {
        $this->container = $container;
        $this->localeCodes = explode('|', $locales);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('country', [$this, 'getCountryName']),
            new TwigFilter('md2html', [$this, 'markdownToHtml'], ['is_safe' => ['html']]),
            new TwigFilter('price', [AppRuntime::class, 'priceFilter']),
            new TwigFilter('htmlRating', [HTMLCodeGenerator::class, 'priceFilter']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('locales', [$this, 'getLocales']),
            new TwigFunction('categoriesCount', [$this, 'categoriesQuantities']),
            new TwigFunction('regionsCount', [$this, 'regionsCount']),
        ];
    }

    /**
     * Return the country name identified by it's code.
     */
    public function getCountryName($code): ?string
    {
        return Intl::getRegionBundle()->getCountryName($code);
    }

    /**
     * Transforms the given Markdown content into HTML content.
     */
    public function markdownToHtml(string $content): string
    {
        return $this->parser->toHtml($content);
    }

    /**
     * Takes the list of codes of the locales (languages) enabled in the
     * application and returns an array with the name of each locale written
     * in its own language (e.g. English, Français, Español, etc.).
     */
    public function getLocales(): array
    {
        if (null !== $this->locales) {
            return $this->locales;
        }

        $this->locales = [];
        foreach ($this->localeCodes as $localeCode) {
            $this->locales[] = ['code' => $localeCode, 'name' => Languages::getName($localeCode, $localeCode)];
        }

        return $this->locales;
    }

    public function getTranslatedCity(string $countryCode, string $cityCode): string
    {
        return $this->container->get('translator')->trans('city.' . $countryCode . '.' . $cityCode);
    }

    public function categoriesQuantities()
    {
        return CategoryCounter::count();
    }

    public function regionsCount()
    {
        return RegionCounter::count();
    }
}
