<?php


namespace App\Controller\EasyAdminCustom\Category;


use App\Entity\Categories;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class CreateController extends AbstractController
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
     * @Route("/easyadmin-custom/category-create", name="easyadmin_custom_category-create")
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function categoryFormCreate(Request $request): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoryFormType::class, $categories);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($categories);
            $manager->flush();

            return $this->redirectToRoute('easyadmin_custom_category', [
                'articles' => $this->entityManager
                    ->createQueryBuilder()
                    ->select('c')
                    ->from('App:Categories', 'c')
                    ->getQuery()
                    ->execute(),
                'key' => 'categories',
            ]);
        }

        return new Response($this->twig->render('easy_admin_custom/formAddCategory.html.twig', [
            'form_add_category' => $form->createView(),
            'add' => 'true',
        ]));
    }
}