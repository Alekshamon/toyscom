<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;

class AppExtensions extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{

    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice($number)
    {
        return number_format($number, 2, ',', ' ') . ' €';
    }

    public function getGlobals(): array
    {
        return [
            'AllCategories' => $this->categoryRepository->findAll(),
        ];
    }
}
