<?php

namespace App\Twig;

use App\Classe\Cart;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtensions extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{

    private $categoryRepository;
    private $cart;

    public function __construct(CategoryRepository $categoryRepository, Cart $cart)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cart = $cart;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
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
            'fullCartQuantity' => $this->cart->fullQuantity(),
            'cartTotal' => $this->cart->getTotal(),
        ];
    }
}
