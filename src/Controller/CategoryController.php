<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {

        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->redirectToRoute('app_home');
        }

        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
