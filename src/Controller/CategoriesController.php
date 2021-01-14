<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CategoriesController extends AbstractController
{

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/categories", name="categories")
     */
    public function index(Environment $twig, CategoriesRepository $categoriesRepository): Response
    {
        return new Response($this->twig->render('categories/index.html.twig', [
            'categorys' => $categoriesRepository->findAll(),
        ]));
    }
}
