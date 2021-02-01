<?php


namespace App\Controller\EasyAdminCustom\Article;


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
     * TODO - у Route - старайся писать типы возможных запросов например @Route(methods={"GET"})
     * @Route("/easyadmin-custom/article-delete/{id}", name="easyadmin_custom_articles_delete")
     * @param Articles $articles
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function articleDelete(Articles $articles): Response
    {
        /**
         * TODO - выдели свю обработку Article в отдельный сервис
         */
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($articles);
        $manager->flush();

        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            // TODO - никогда так не пиши, не делай QB при передаче в функцию сделай отдельной переменной $articles = $this-> ...
            'articles' => $this->entityManager
                ->createQueryBuilder()
                ->select('a')
                ->from('App:Articles', 'a')
                ->getQuery()
                ->execute(),
            'key' => 'article',
        ]));
    }
}
