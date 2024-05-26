<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{


    public function __construct(private RequestStack $requestStack)
    {
    }

    /*
     * add()
     * Fonction permettant l'ajout d'un produit au panier
     */
    public function add($product)
    {
        // Appeler la session CART de symfony
        $cart = $this->requestStack->getSession()->get('cart');

        // Ajouter une qtity +1 à mon produit
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1
            ];
        }

        // Créer ma session Cart
        $this->requestStack->getSession()->set('cart', $cart);
    }


    /*
     * decrease()
     * Fonction permettant de retirer un produit du panier
     */
    public function decrease($id)
    {
        // Appeler la session CART de symfony
        $cart = $this->requestStack->getSession()->get('cart');

        // Ajouter une qtity +1 à mon produit
        if ($cart[$id]['qty'] > 1) {
            $cart[$id] = [
                'object' => $cart[$id]['object'],
                'qty' => $cart[$id]['qty'] - 1
            ];
        } else {
            unset($cart[$id]);
        }

        // Créer ma session Cart
        $this->requestStack->getSession()->set('cart', $cart);
    }


    /*
     * fullQuantity()
     * Fonction permettant de retourner la quantité total de produit dans le panier
     */
    public function fullQuantity()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $total = 0;
        if ($cart) {
            foreach ($cart as $product) {
                $total += $product['qty'];
            }
        }
        return $total;
    }


    /*
     * getTotal()
     * Fonction permettant de retourner le prix total du panier
     */
    public function getTotal()
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $totalPrice = 0;
        foreach ($cart as $product) {
            $totalPrice += $product['object']->getPrice() * $product['qty'];
        }
        return number_format($totalPrice, 2, '.', '');
    }

    /*
     * remove()
     * Fonction permettant de vider le panier
     */
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }


    /*
     * getCart()
     * Fonction permettant de retourner le panier
     */
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
}
