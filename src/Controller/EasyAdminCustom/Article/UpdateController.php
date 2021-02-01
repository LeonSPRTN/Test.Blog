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

        return new Response($this->twig->render('easy_admin_custom/formAddArticles.html.twig', [
            'form_add_article' => $form->createView(),
            'add' => 'false',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/article-update-ajax")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function updateArticle(Request $request): Response
    {
        $articleForm = $request->get('articles_form');

        $datetime = date('YmdHis',mktime((int)$articleForm['Date']['time']['hour'], (int)$articleForm['Date']['time']['minute'], null,
            (int)$articleForm['Date']['date']['month'], (int)$articleForm['Date']['date']['day'], (int)$articleForm['Date']['date']['year']) );

        $article = $this->entityManager->getRepository(Articles::class)->find($articleForm['id']);
        $article->setName($articleForm['Name']);
        $article->setHeadline($articleForm['Headline']);
        $article->setDate(new \DateTime($datetime));
        $article->setArticleText($articleForm['ArticleText']);
        $article->setCategory($this->entityManager->getRepository(Categories::class)->find($articleForm['Category']));
        $this->entityManager->flush();

        return new Response();
    }
}
