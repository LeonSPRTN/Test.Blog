<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesFormType;
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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        $articles = new Articles();
        $form = $this->createForm(ArticlesFormType::class, $articles);

        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
            'form_add_article' => $form->createView(),
        ]));
    }
}
