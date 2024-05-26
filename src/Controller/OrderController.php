<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
# 1 etape du tunnel d'achat
# choix de l'adresse de livraison et du transporteur

{
    #[Route('/order', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAdresses();

        if (count($addresses) == 0) {

            return $this->redirectToRoute('app_account_address_form');
        }

        $form = $this->createForm(OrderUserType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary'),
        ]);

        return $this->render('order/index.html.twig', [
            'deliverForm' => $form->createView(),
        ]);
    }

    /*
     * 2ème étape du tunnel d'achat
     * Récap de la commande de l'utilisateur
     * Insertion en base de donnée
     * Préparation du paiement vers Stripe
     */


    #[Route('/order/summary', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {


        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }
        $products = $cart->getCart();
        $form = $this->createForm(OrderUserType::class, null, [
            'addresses' => $this->getUser()->getAdresses(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Création de la chaîne adresse
            $addressObj = $form->get('addresses')->getData();

            $address = $addressObj->getFirstname() . ' ' . $addressObj->getLastname() . '<br/>';
            $address .= $addressObj->getAddress() . '<br/>';
            $address .= $addressObj->getPostalCode() . ' ' . $addressObj->getCity() . '<br/>';
            $address .= $addressObj->getCountry() . '<br/>';
            $address .= $addressObj->getPhone();

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($address);

            foreach ($products as $product) {
                $orderDetail = new OrderDetail();
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setProductIllustration($product['object']->getIllustration());
                $orderDetail->setProductPrice($product['object']->getPrice());
                //$orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductQuantity($product['qty']);
                $order->addOrderDetail($orderDetail);
            }

            $entityManager->persist($order);
            $entityManager->flush();
        }
        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products,
            'order' => $order,
            'total' => $cart->getTotal(),
        ]);
    }
}
