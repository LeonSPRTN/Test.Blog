<?php

namespace App\Controller;

use App\Form\ArticleFormType;
use App\Repository\ArticlesRepository;
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
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        $form = $this->createForm(ArticleFormType::class, $articlesRepository);

        try {
            return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
                //'articles' => $articlesRepository->findAll(),
                'form_add_article' => $form->createView(),
            ]));
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return new Response($e);
        }
    }
}
