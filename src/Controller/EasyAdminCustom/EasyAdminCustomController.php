<?php

namespace App\Controller\EasyAdminCustom;

use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EasyAdminCustomController extends AbstractController
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
     * @Route("/easyadmin-custom/article", name="easyadmin_custom_article")
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexArticles(): Response
    {
        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'articles' => $this->entityManager
                ->createQueryBuilder()
                ->select('a')
                ->from('App:Articles', 'a')
                ->orderBy('a.Date', 'DESC')
                ->getQuery()
                ->execute(),
            'key' => 'article',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/category", name="easyadmin_custom_category")
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexCategory( ): Response
    {
        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'categories' => $this->entityManager
                ->createQueryBuilder()
                ->select('c')
                ->from('App:Categories', 'c')
                ->orderBy('c.Name', 'DESC')
                ->getQuery()
                ->execute(),
            'key' => 'category',
        ]));
    }










}
