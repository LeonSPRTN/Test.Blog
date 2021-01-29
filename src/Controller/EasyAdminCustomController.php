<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Form\ArticlesFormType;
use App\Form\CategoryFormType;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @var ArticlesRepository
     */
    private $articlesRepository;

    /**
     * @var CategoriesRepository
     */
    private $categoriesRepository;

    public function __construct(Environment $twig, ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->articlesRepository = $articlesRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/easyadmin-custom", name="easy_admin_custom")
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexArticles(): Response
    {
        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'articles' => $this->entityManager->createQueryBuilder()
                ->select('a')
                ->from('App:Articles', 'a')
                ->orderBy('a.Date', 'DESC')
                ->getQuery()
                ->execute(),
            'key' => 'articles',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/articles-add", name="easy_admin_custom_articles_add")
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function articlesFromAdd(Request $request): Response
    {
        $articles = new Articles();
        $form = $this->createForm(ArticlesFormType::class, $articles);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($articles);
            $manager->flush();

            return $this->redirectToRoute('easy_admin_custom', [
                'articles' => $this->articlesRepository->findAll(),
                'key' => 'articles',
            ]);
        }

        return new Response($this->twig->render('easy_admin_custom/formAddArticles.html.twig', [
            'form_add_article' => $form->createView(),
            'add' => 'true',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/categories", name="easy_admin_custom_categories")
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexCategory(): Response
    {
        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'categories' => $this->entityManager->createQueryBuilder()
                ->select('c')
                ->from('App:Categories', 'c')
                ->getQuery()
                ->execute(),
            'key' => 'categories',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/categories-add", name="easy_admin_custom_categories-add")
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function categoryFormAdd(Request $request): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoryFormType::class, $categories);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($categories);
            $manager->flush();

            return $this->redirectToRoute('easy_admin_custom_categories', [
                'articles' => $this->entityManager->createQueryBuilder()
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

    /**
     * @Route("/easyadmin-custom/articles-del-id-{id}", name="easy_admin_custom_articles_delete")
     * @param Articles $articles
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function articleDelete(Articles $articles): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($articles);
        $manager->flush();

        return new Response($this->twig->render('easy_admin_custom/index.html.twig', [
            'articles' => $this->articlesRepository->findAll(),
            'key' => 'articles',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/category-del-id-{id}", name="easy_admin_custom_category_delete")
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
            'categories' => $this->categoriesRepository->findAll(),
            'key' => 'categories',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/article-update-{id}", name="easy_admin_custom_article_update")
     * @param Articles $articles
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function articleUpdate(Articles $articles): Response
    {
        $form = $this->createForm(ArticlesFormType::class, $articles, array(
             'attr'=> array(
                'class' => 'form-ajax-update-article'
            )
        ));

        return new Response($this->twig->render('easy_admin_custom/formAddArticles.html.twig', [
            'form_add_article' => $form->createView(),
            'add' => 'false',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/update-article", name="easyadmin-custom-update-article")
     * @return Response
     */
    public function articleUpdatePost(): Response
    {
        $param = $_POST['articles_form'];

        $article = $this->entityManager->getRepository(Articles::class)->find($param['id']);

        $hour = (int)$param['Date']['time']['hour'];
        $minute = (int)$param['Date']['time']['minute'];
        $month = (int)$param['Date']['date']['month'];
        $day = (int)$param['Date']['date']['day'];
        $year = (int)$param['Date']['date']['year'];

        $datetime = date('YmdHi',mktime($hour, $minute, null, $month, $day, $year));

        $article->setName($param['Name'])
            ->setDate(new DateTime($datetime))
            ->setArticleText($param['ArticleText'])
            ->setHeadline($param['Headline'])
            ->setCategory($this->entityManager->getRepository(Categories::class)->find($param['Category']));
        $this->entityManager->flush();

        return new Response();
    }

    /**
     * @Route("/easyadmin-custom/category-update-{id}", name="easy_admin_custom_category_update")
     * @param Categories $categories
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function categoryUpdate(Categories $categories): Response
    {
        $form = $this->createForm(CategoryFormType::class, $categories, array(
            'attr'=> array(
                'class' => 'form-ajax-update-category'
            )
        ));

        return new Response($this->twig->render('easy_admin_custom/formAddArticles.html.twig', [
            'form_add_article' => $form->createView(),
            'add' => 'false',
        ]));
    }

    /**
     * @Route("/easyadmin-custom/update-category", name="easyadmin-custom-update-category")
     * @return Response
     */
    public function categoryUpdatePost(): Response
    {
        $param = $_POST['category_form'];

        /*$article = $this->entityManager->getRepository(Categories::class)->find($param['id']);

        $article->setName($param['Name']);
        $this->entityManager->flush();*/

        return new Response();
    }

}
