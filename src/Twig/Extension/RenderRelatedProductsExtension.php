<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCrossSellingPlugin\Twig\Extension;

use BitBag\SyliusCrossSellingPlugin\Finder\RelatedProductsFinderInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RenderRelatedProductsExtension extends AbstractExtension
{
    private const DEFAULT_COUNT = 4;

    /** @var RelatedProductsFinderInterface */
    private $relatedProductsFinder;

    /** @var Environment */
    private $twig;

    /** @var string */
    private $defaultTemplate;

    public function __construct(
        RelatedProductsFinderInterface $relatedProductsFinder,
        Environment $twig,
        string $defaultTemplate
    ) {
        $this->relatedProductsFinder = $relatedProductsFinder;
        $this->twig = $twig;
        $this->defaultTemplate = $defaultTemplate;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'bitbag_crossselling_render_related_products',
                [$this, 'renderRelatedProducts'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function renderRelatedProducts(
        string $slug,
        int $count = self::DEFAULT_COUNT,
        ?string $template = null
    ): string {
        $template = $template ?? $this->defaultTemplate;

        $products = $this->relatedProductsFinder->findRelatedInCurrentChannelBySlug($slug, $count);

        return $this->twig->render($template, [
            'products' => $products,
        ]);
    }
}
