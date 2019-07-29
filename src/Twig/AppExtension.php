<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Twig;

use App\Utils\AppUtils;
use App\Utils\CategoryCounter;
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
            new TwigFunction('categories', [$this, 'getCategories']),
            new TwigFunction('price', [$this, 'priceFilter']),
            new TwigFunction('transCity', [$this, 'getTranslatedCity']),
            new TwigFunction('getCurrency', [$this, 'getCurrency']),
            new TwigFunction('htmlRating', [HTMLCodeGenerator::class, 'htmlRating']),
            new TwigFunction('citiesByCountry', [$this, 'citiesByCountry']),
            new TwigFunction('loadQuantites', [$this, 'categoriesQuantities']),
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

    public function getCategories(): array
    {
        return AppUtils::getCategories();
    }

    public function priceFilter($number, $currency = 'MR', $decimals = 0, $decPoint = '.', $thousandsSep = ' ')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price . ' ';
        switch ($currency) {
            case 'MR':
                $price .= ' MRU';

                break;
            case 'SN':
                $price .= 'FCFA';

                break;
            case 'ML':
                $price .= 'FCFA';

                break;
        }

        return $price;
    }

    public function getTranslatedCity(string $countryCode, string $cityCode): string
    {
        return $this->container->get('translator')->trans('city.' . $countryCode . '.' . $cityCode);
    }

    public function getCurrency(string $code)
    {
        $currency = '';
        switch ($code) {
            case 'MR':
                $currency .= 'MRU';

                break;
            case 'SN':
                $currency .= 'FCFA';

                break;
            case 'ML':
                $currency .= 'FCFA';

                break;
            default:
                throw new \Exception('Unknown country');

                break;
        }

        return $currency;
    }

    public function citiesByCountry(string $country = 'MR'): ?array
    {
        $cities = json_decode(file_get_contents(__DIR__ . '/../Utils/CityFinder/cities.json'), true);

        return $cities[$country] ?: null;
    }

    public function categoriesQuantities()
    {
        return CategoryCounter::count();
    }
}
