<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class BlogController extends AbstractController
{

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="homepage")
     */
    #public function index(): Response
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return new Response($this->twig->render('blog/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]));
    }

    /**
     * @Route("/blog/{id}", name="blog")
     */
    public function show(Articles $articles): Response
    {
        return new Response($this->twig->render('blog/show.html.twig', [
            'article' => $articles,
        ]));
    }
}
