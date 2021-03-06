<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BlogController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="app_homepage")
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(): Response
    {
        return new Response($this->twig->render('blog/index.html.twig', [
            'articles' => $this->entityManager
                ->createQueryBuilder()
                ->select('a')
                ->from('App:Articles', 'a')
                ->orderBy('a.Date', 'DESC')
                ->getQuery()
                ->execute(),
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
