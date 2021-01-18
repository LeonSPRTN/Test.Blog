<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BlogController extends AbstractController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="homepage")
     * @param ArticlesRepository $articlesRepository
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return new Response($this->twig->render('blog/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]));
    }

    /**
     * @Route("/blog/{id}", name="blog")
     * @param Articles $articles
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function show(Articles $articles): Response
    {
        return new Response($this->twig->render('blog/show.html.twig', [
            'article' => $articles,
        ]));
    }
}
