<?php


namespace App\Controller\EasyAdminCustom\Article;


use App\Entity\Articles;
use App\Form\ArticlesFormType;
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
     * @Route("/easyadmin-custom/article-create", name="easyadmin_custom_articles_create")
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function articleFormCreate(Request $request): Response
    {
        $articles = new Articles();
        $form = $this->createForm(ArticlesFormType::class, $articles);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // TODO - выдели обработку в отдельный сервис - например ArticleHandler
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($articles);
            $manager->flush();

            return $this->redirectToRoute('easyadmin_custom_article', [
                // TODO - никогда так не пиши, не делай QB при передаче в функцию сделай отдельной переменной $articles = $this-> ...
                'articles' => $this->entityManager
                    ->createQueryBuilder()
                    ->select('a')
                    ->from('App:Articles', 'a')
                    ->getQuery()
                    ->execute(),
                'key' => 'articles',
            ]);
        }

        return new Response($this->twig->render('easy_admin_custom/formAddArticles.html.twig', [
            'form_add_article' => $form->createView(),
            'add' => 'true',
        ]));
    }
}
