<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Classe\Cart;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/compte/modify-pwd', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {


        $user = $this->getUser();
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'Votre mot de passe a bien été modifié.'

            );
        }
        return $this->render('account/password.html.twig', [
            'modifyPwd' => $form->createView(),
        ]);
    }

    #[Route('/compte/adresse', name: 'app_account_addresses')]
    public function addresses(): Response
    {
        return $this->render('account/addresses.html.twig');
    }

    #[Route('/compte/adresses/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);
        if (!$address or $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_addresses');
        }
        $this->addFlash(
            'success',
            "Cette adresse a été supprimée"
        );

        $this->entityManager->remove($address);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_account_addresses');
    }


    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_address_form', defaults: ['id' => null])]
    public function form(Request $request, $id, AddressRepository $addressRepository, Cart $cart): Response
    {
        if ($id) {
            $address = $addressRepository->findOneById($id);
            if (!$address or $address->getUser() != $this->getUser()) {
                return $this->redirectToRoute('app_account_addresses');
            }
        } else {
            $address = new Adresse();
            $address->setUser($this->getUser());
        }

        $form = $this->createForm(AddressUserType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Votre adresse est correctement sauvegardée."
            );

            if ($cart->fullQuantity() > 0) {
                return $this->redirectToRoute("app_order");
            }

            return $this->redirectToRoute("app_account_addresses");
        }

        return $this->render('account/addressForm.html.twig', [
            'addressForm' => $form
        ]);
    }
}
