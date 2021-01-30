<?php


namespace App\Controller\EasyAdminCustom\Category;


use App\Entity\Categories;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class UpdateController extends AbstractController
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
     * @Route("/easyadmin-custom/category-update/{id}", name="easyadmin_custom_category_update")
     * @param Categories $categories
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function viewFormCategory(Categories $categories): Response
    {
        $form = $this->createForm(CategoryFormType::class, $categories, array(
            'attr'=>array(
                'class'=>'form-ajax-update-category')
        ));

        return new Response($this->twig->render('easy_admin_custom/formAddCategory.html.twig', [
            'form_add_category' => $form->createView(),
            'add' => 'false',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/category-update-ajax")
     * @return Response
     */
    public function updateCategory(): Response
    {
        $categoryForm = $_REQUEST['category_form'];

        $category = $this->entityManager->getRepository(Categories::class)->find($categoryForm['id']);
        $category->setName($categoryForm['Name']);
        $this->entityManager->flush();

        return new Response();
    }
}