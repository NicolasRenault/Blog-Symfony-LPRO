<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Form\CommentType;
use App\TestServices\Logger;
use App\TestServices\Spam;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Expression;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Contracts\Translation\Test\TranslatorTest;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/blog")
 * Class ActionController
 * @package App\Controller
 */

class ActionController extends AbstractController
{
    public function getDoctrineManager() {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/page/{page}",
     *  defaults={"page": "1"},
     *  requirements={"page": "\d+"},
     *  name="action_list_page"
     * )
     */
    public function listPageAction($page)
    {
        $this->flashBag(array($page));
        return new Response("Hello World ! $page LISTACTION");
    }

    /**
     * @Route("/article/{articleId}",
     *  requirements={"articleId": "\d+"},
     *  name="action_view"
     * )
     */
    public function viewAction($articleId)
    {
        $this->flashBag(array($articleId));
        $article = $this->getDoctrineManager()->getRepository('App:Article')->findOneBy(array('id' => $articleId, 'published' => true));
        if ($article != null) {
            $this->incrementeArticleViews($article);
            $comments = $article->getComments()->getValues();
            $categories = $article->getCategories()->getValues();
            return $this->render('view.html.twig',['article' => $article, 'comments' => $comments, 'categories' => $categories]);
        } else {
            throw new NotFoundHttpException("Article non publié ou inconnu");
        }
    }

    /**
     * @Route("/article/add",
     * name="action_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addAction(Request $request, TranslatorInterface $translator, Spam $spam, Logger $logger)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->add('send', SubmitType::class, ['label' => $translator->trans("Ajouter l'article")]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && !$spam->isSpam($article->getContent(), $request, $translator)) {
            $this->getDoctrineManager()->persist($form->getData()->setCreatedAt(new DateTime())->setUpdatedAt(new DateTime()));
            $this->getDoctrineManager()->flush();
            return $this->redirectToRoute('action_list');
        }

        return $this->render('add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/list/{page}",
     *  defaults={"page": "1"},
     *  requirements={"page": "\d+"},
     * name="action_list"
     * )
     */
    public function listAction($page)
    {
        $flashArray = array($page);
        $nbPersPerPage = $this->getParameter('nbArticlesPerPage');
        $articles = $this->getDoctrineManager()->getRepository('App:Article')->findAllWithPaging($page, $nbPersPerPage);
        $nbArticle = $articles->count();
        $nbPage = intval(ceil($nbArticle / $nbPersPerPage));
        if($page > $nbPage) {
            $page = $nbPage;
        }
        $flashArray = array_merge($flashArray,array($nbPersPerPage, $nbPage, $nbArticle, $page));
        if ($articles != []) {
            $this->flashBag($flashArray);
            return $this->render('list.html.twig', ['articles' => $articles, 'nbPage' => $nbPage, 'currentPage' => $page]);
        } else {
            throw new NotFoundHttpException("Aucun article disponible");
        }
    }

    /**
     * @Route("/article/delete/{articleId}",
     *  requirements={"articleId": "\d+"},
     * name="action_delete"
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteAction($articleId)
    {
        $this->flashBag(array($articleId));
        $article = $this->getDoctrineManager()->getRepository('App:Article')->findOneBy(array('id' => $articleId));
        if ($article != null) {
            $this->getDoctrineManager()->remove($article);
            $this->getDoctrineManager()->flush();
            return $this->redirectToRoute('action_list');
        } else {
            throw new NotFoundHttpException("Article inconnu");
        }
    }

    /**
     * @Route("/article/edit/{articleId}",
     *  requirements={"articleId": "\d+"},
     * name="action_edit"
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function editAction($articleId, Request $request, TranslatorInterface $translator)
    {

        $article = $this->getDoctrineManager()->getRepository('App:Article')->findOneBy(array('id' => $articleId));
        $form = $this->createForm(ArticleType::class, $article);
        $form->add('send', SubmitType::class, ['label' => $translator->trans("Editer l'article")]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrineManager()->persist($form->getData()->setUpdatedAt(new DateTime()));
            $this->getDoctrineManager()->flush();
            return $this->redirectToRoute('action_list');
        }

        return $this->render('add.html.twig', array('form' => $form->createView(), 'articleId' => $articleId));
        $this->flashBag(array($articleId));
        return $this->render('edit.html.twig');
    }

    /**
     * @Route("/category/{categoryId}",
     *  requirements={"categoryId": "\d+"},
     *  name="action_category"
     * )
     */
    public function categoryAction($categoryId, TranslatorInterface $translator)
    {
        $flashArray = array($categoryId);
        $category = $this->getDoctrineManager()->getRepository('App:Category')->findOneWithArticles($categoryId);
        $articles = $category->getArticles()->getValues();
        if ($articles != []) {
            $this->flashBag($flashArray);
            return $this->render('list.html.twig', ['articles' => $articles, 'category' => $category]);
        } else {
            return $this->render('list.html.twig', ['message' => $translator->trans('Aucun article disponnible'), 'category' => $category]);
        }
    }

    /**
     * @Route("/category/add",
     * name="action_add_category")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addCategory(Request $request, TranslatorInterface $translator)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->add('send', SubmitType::class, ['label' => $translator->trans("Ajouter la catégorie")]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrineManager()->persist($form->getData());
            $this->getDoctrineManager()->flush();
            return $this->redirectToRoute('action_list');
        }

        return $this->render('add_category.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/category/delete/{categoryId}",
     *  requirements={"caregoryId": "\d+"},
     * name="action_delete_category"
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteCategory($categoryId)
    {
        $this->flashBag(array($categoryId));
        $category = $this->getDoctrineManager()->getRepository('App:Category')->findOneBy(array('id' => $categoryId));
        if ($category != null) {
            $this->getDoctrineManager()->remove($category);
            $this->getDoctrineManager()->flush();
            return $this->redirectToRoute('action_list');
        } else {
            throw new NotFoundHttpException("Catégorie inconnu");
        }
    }

    /**
     * @Route("/comment/add/{articleId}",
     * requirements={"articleId": "\d+"},
     * name="action_add_comment")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addComment($articleId,  Request $request, TranslatorInterface $translator)
    {
        $comment = new Comment();
        $article = $this->getDoctrineManager()->getRepository('App:Article')->findOneBy(array('id' => $articleId));
        $form = $this->createForm(CommentType::class, $comment);
        $form->add('send', SubmitType::class, ['label' => $translator->trans("Ajouter un commentaire")]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrineManager()->persist($form->getData()->setCreatedAt(new DateTime())->setArticle($article));
            $this->getDoctrineManager()->flush();
            return $this->viewAction($articleId);
        }

        return $this->render('add_comment.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/comment/delete/{commentId}",
     *  requirements={"commentyId": "\d+"},
     * name="action_delete_comment"
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteComment($commentId)
    {
        $this->flashBag(array($commentId));
        $comment = $this->getDoctrineManager()->getRepository('App:Comment')->findOneBy(array('id' => $commentId));
        if ($comment != null) {
            $articleId = $comment->getArticle()->getId();
            $this->getDoctrineManager()->remove($comment);
            $this->getDoctrineManager()->flush();
            return $this->viewAction($articleId);
        } else {
            throw new NotFoundHttpException("Commentaire inconnu");
        }
    }

    /**
     * @Route("/article/categories/{articleId}",
     *  requirements={"articleId": "\d+"},
     * name="action_article_categories"
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function addCategoriesToArticle($articleId,  Request $request, TranslatorInterface $translator)
    {
        $article = $this->getDoctrineManager()->getRepository('App:Article')->findOneBy(array('id' => $articleId));
        $categories = $this->getDoctrineManager()->getRepository('App:Category')->findAll();
        $formCategories = array();
        foreach ($categories as $category) {
            $selected = '';
            if ($category->getArticles()->contains($article)) {
                $selected = 'checked';
            }
            $formCategories[] = array('name' => $category->getName(), 'id' => $category->getId(), 'selected' => $selected);
        }
        // dd($formCategories);
        return $this->render('article_categories.html.twig', array('categories' => $formCategories, 'articleId' => $articleId));
    }

    /**
     * @Route("/article/categories/validate",
     * name="action_article_categories_validate")
     *
     * @return void
     */
    public function validateFormCategory(Request $request)
    {
        $articleId = $request->request->get('articleId');
        $article = $this->getDoctrineManager()->getRepository('App:Article')->findOneBy(array('id' => $articleId));
        $articleCategories = $article->getCategories()->getValues();
        $allCategories = $this->getDoctrineManager()->getRepository('App:Category')->findAll();

        foreach ($allCategories as $category) {
            $formCategoryId = $request->request->get(strval($category->getId()));

            if ($formCategoryId != null && !in_array($category, $articleCategories)) {
                $article->addCategory($category);
            } else if ($formCategoryId == null && in_array($category, $articleCategories)){
                $article->removeCategory($category);
            }
        }
        return $this->viewAction($articleId);
    }

    /**
     * Send to session every flashBag in param
     *
     * @param [array] $param
     * @return void
     */
    private function flashBag($param)
    {
        foreach ($param as $value) {
            $this->addFlash('info', $value);
        }
    }

    /**
     * Return the twig layout with the array of last articles
     *
     * @param [int] $nbArticle
     * @return Response
     */
    public function getLeftMenu($nbArticle)
    {
        $articles = $this->getDoctrineManager()->getRepository('App:Article')->findBy(
            array('published' => true),
            array('created_at' => 'desc'),
            $nbArticle,
            0
        );

        $categories = $this->getDoctrineManager()->getRepository('App:Category')->findAll();
        foreach ($categories as $category) {
            $category->countNbArticles();
        }
        return $this->render('last_articles.html.twig', ['articles' => $articles, 'categories' => $categories]);
    }

    /**
     * Incremente the article's nbViews and persist it
     *
     * @param [Article] $article
     * @return void
     */
    public function incrementeArticleViews($article)
    {
        $article->setNbViews($article->getNbViews() + 1);
        $this->getDoctrineManager()->persist($article);
        $this->getDoctrineManager()->flush();
    }
}
