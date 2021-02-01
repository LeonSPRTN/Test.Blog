<?php


namespace App\Controller\EasyAdminCustom\Article;


use App\Entity\Articles;
use App\Entity\Categories;
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

/**
 * TODO - по возможности пиши везде аннотации и описания
 * Update controller
 */
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
     * @Route("/easyadmin-custom/article-update/{id}", name="easyadmin_custom_article_update")
     * @param Articles $articles
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function viewFormArticle(Articles $articles): Response
    {
        $form = $this->createForm(ArticlesFormType::class, $articles, array(
            'attr'=>array(
                'class'=>'form-ajax-update-article')
        ));

        /**
         * TODO - шаблоны переименуй в единственное число, подумай как можно разделить этот функционал чтобы не через флаг рендерились  разные формы
         */
        return new Response($this->twig->render('easy_admin_custom/formAddArticles.html.twig', [
            'form_add_article' => $form->createView(),
            'add' => 'false',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/article-update-ajax")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * TODO тут посмотри мои пометки
     * @return Response
     * @throws \Exception
     */
    public function updateArticle(Request $request): Response
    {
        $articleForm = $request->get('articles_form');

        // Можно вот так получить post  переменные
        //$request->request->get('articles_form');

        // а  вот так получить get  переменные
        //$request->query->get('articles_form');
        $date = $articleForm['Date'];

        $dateTime = new \DateTime();
        $dateTime->setDate($date['date']['year'],$date['date']['month'],$date['date']['day'] );
        $dateTime->setTime($date['time']['hour'], $date['time']['minute'], null);
        $dateTime->format('YmdHis');

        /**
         * TODO переименовать все сущности в единственное число - так ты можешь делать себе пометки в PHPStorm , если что то не доделал.
         */

        /**
         * TODO - тут сделай получение сущностей через QueryBuilder
         */
        $article = $this->entityManager->getRepository(Articles::class)->find($articleForm['id']);
        $article->setName($articleForm['Name']);
        $article->setHeadline($articleForm['Headline']);
        $article->setDate($dateTime);
        $article->setArticleText($articleForm['ArticleText']);

        /**
         * TODO - тут сделай получение сущностей через QueryBuilder
         */
        $article->setCategory($this->entityManager->getRepository(Categories::class)->find($articleForm['Category']));
        $this->entityManager->flush();

        return new Response();
    }
}
