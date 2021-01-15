<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Form\ArticlesFormType;
use App\Form\CategoryFormType;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return Response
     */
    public function articlesFromAdd(): Response
    {
        $articles = new Articles();
        $form = $this->createForm(ArticlesFormType::class, $articles);

        return new Response($this->twig->render('easy_admin_custom/formAddArticles.html.twig', [
            'form_add_article' => $form->createView(),
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
     * @return Response
     */
    public function categoryFormAdd(): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoryFormType::class, $categories);

        return new Response($this->twig->render('easy_admin_custom/formAddCategory.html.twig', [
            'form_add_category' => $form->createView(),
        ]));
    }
}
