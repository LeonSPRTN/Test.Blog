<?php


namespace App\Controller\EasyAdminCustom\Category;


use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class DeleteController extends AbstractController
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
     * @Route("/easyadmin-custom/category-delete/{id}", name="easyadmin_custom_category_delete")
     * @param Categories $categories
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function categoryDelete(Categories $categories): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($categories);
        $manager->flush();

        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'categories' => $this->entityManager
                ->createQueryBuilder()
                ->select('c')
                ->from('App:Categories', 'c')
                ->getQuery()
                ->execute(),
            'key' => 'category',
        ]));
    }
}