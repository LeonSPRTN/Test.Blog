<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Form\ArticlesFormType;
use App\Form\CategoryFormType;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EasyAdminCustomController extends AbstractController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/easyadmin-custom", name="easy_admin_custom")
     * @param ArticlesRepository $articlesRepository
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexArticles(ArticlesRepository $articlesRepository): Response
    {
        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
            'key' => 'articles',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/articles-add", name="easy_admin_custom_articles_add")
     * @param ArticlesRepository $articlesRepository
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function articlesFromAdd(ArticlesRepository $articlesRepository, Request $request): Response
    {
        $articles = new Articles();
        $form = $this->createForm(ArticlesFormType::class, $articles);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($articles);
            $manager->flush();

            return $this->redirectToRoute('easy_admin_custom', [
                'articles' => $articlesRepository->findAll(),
                'key' => 'articles',
            ]);
        }

        return new Response($this->twig->render('easy_admin_custom/formAddArticles.html.twig', [
            'form_add_article' => $form->createView(),
            'add' => 'true',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/categories", name="easy_admin_custom_categories")
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexCategory(CategoriesRepository $categoriesRepository): Response
    {
        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'key' => 'categories',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/categories-add", name="easy_admin_custom_categories-add")
     * @param CategoriesRepository $categoriesRepository
     * @param $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function categoryFormAdd(CategoriesRepository $categoriesRepository, Request $request): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoryFormType::class, $categories);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($categories);
            $manager->flush();

            return $this->redirectToRoute('easy_admin_custom_categories', [
                'articles' => $categoriesRepository->findAll(),
                'key' => 'categories',
            ]);
        }

        return new Response($this->twig->render('easy_admin_custom/formAddCategory.html.twig', [
            'form_add_category' => $form->createView(),
            'add' => 'true',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/articles-del-id-{id}", name="easy_admin_custom_articles_delete")
     * @param ArticlesRepository $articlesRepository
     * @param Articles $articles
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function articleDelete(ArticlesRepository $articlesRepository, Articles $articles): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($articles);
        $manager->flush();

        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
            'key' => 'articles',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/category-del-id-{id}", name="easy_admin_custom_category_delete")
     * @param CategoriesRepository $categoriesRepository
     * @param Categories $categories
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function categoryDelete(CategoriesRepository $categoriesRepository, Categories $categories): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($categories);
        $manager->flush();

        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'key' => 'categories',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/article-update-{id}", name="easy_admin_custom_article_update")
     * @param Articles $articles
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function articleUpdate(Articles $articles): Response
    {
        $form = $this->createForm(ArticlesFormType::class, $articles);

        return new Response($this->twig->render('easy_admin_custom/formAddArticles.html.twig', [
            'form_add_article' => $form->createView(),
            'add' => 'false',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/category-update-{id}", name="easy_admin_custom_category_update")
     * @param Categories $categories
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function categoryUpdate(Categories $categories): Response
    {
        $form = $this->createForm(CategoryFormType::class, $categories);

        return new Response($this->twig->render('easy_admin_custom/formAddArticles.html.twig', [
            'form_add_article' => $form->createView(),
            'add' => 'false',
        ]));
    }
}
