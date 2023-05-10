<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\CategorySearch;
use App\Form\CategorySearchType;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('/list', name: 'categories.list')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/new', name: 'categories.new')]
    public function newCategory(Request $request, ManagerRegistry $manager): Response {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $categoryManager = $manager->getManager();
            $categoryManager->persist($category);
            $categoryManager->flush();

            return $this->redirectToRoute('categories.new');
        } else {
            return $this->render('category/new.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }


}
