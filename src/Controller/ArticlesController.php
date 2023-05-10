<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\CategorySearch;
use App\Entity\PriceSearch;
use App\Entity\PropertySearch;
use App\Form\ArticleFormType;
use App\Form\CategorySearchType;
use App\Form\PriceSearchType;
use App\Form\PropertySearchType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Summary of ArticlesController
 */
class ArticlesController extends AbstractController {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route('/articles', name: 'app_articles')]
    public function index(Request $request): Response {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$propertySearch);
        $form->handleRequest($request);

        $articles= [];
        $repo = $this->em->getRepository(Article::class);

        if($form->isSubmitted()) {
            $name = $propertySearch->getName();

            if ($name !== '') {
                $articles = $repo->findBy(['name' => $name]);
            }
        } else {
            $articles = $repo->findAll();
        }


        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'form' => $form->createView()
        ]);
    }

    #[Route('/articles/details/{id}', methods: ["GET"], name: 'article_details')]
    public function show_details($id): Response {
        $repo = $this->em->getRepository(Article::class);

        $article = $repo->find($id);

        return $this->render('articles/show.html.twig', [
            "article" => $article
        ]);
    }

    #[Route('/articles/create', name: 'new_article')]
    public function new(Request $req): Response {
        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($req);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $newArticle = $form->getData();

            $this->em->getRepository(Article::class)->save($newArticle, true);

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/articles/edit/{id}', name: 'edit_article')]
    public function edit($id, Request $req) : Response {
        $article = $this->em->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setName($form->get('name')->getData());
            $article->setPrice($form->get('price')->getData());

            $this->em->flush();

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
            // 'article' => $article
        ]);
    }

    #[Route('/articles/delete/{id}', name: 'delete_article')]
    public function delete($id): Response {
        $repo = $this->em->getRepository(Article::class);
        
        $article = $repo->find($id);

        $repo->remove($article, true);

        return $this->redirectToRoute('app_articles');

    }

    #[Route('/art_cat/', name: 'article_par_cat')]
    public function articlesParCategorie(Request $request, \Symfony\Bridge\Doctrine\ManagerRegistry
                                                 $doctrine) {
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class,$categorySearch);
        $form->handleRequest($request);
        $articles= [];
        if($form->isSubmitted() && $form->isValid()) {
            $category = $categorySearch->getCategory();
            if ($category!="")
                $articles= $category->getArticles();
            else
                $articles= $doctrine->getRepository(Article::class)->findAll();
        }
        return $this->render('articles/articlesParCategorie.html.twig',[
            'form' => $form->createView(),
            'articles' => $articles]);
    }

    #[Route('/art_prix/', name: 'article_par_prix')]
    public function articlesParPrix(Request $request, EntityManagerInterface
                                            $entityManager) {
        $priceSearch = new PriceSearch();
        $form = $this->createForm(PriceSearchType::class,$priceSearch);
        $form->handleRequest($request);
        $articles= [];
        if($form->isSubmitted() && $form->isValid()) {
            $minPrice = $priceSearch->getMinPrice();
            $maxPrice = $priceSearch->getMaxPrice();
            $articles = $entityManager->getRepository(Article::class)->findByPriceRange($minPrice, $maxPrice);
}
        return $this->render('articles/articlesParPrix.html.twig',[ 'form' =>$form->createView(), 'articles' => $articles]);
}
}
