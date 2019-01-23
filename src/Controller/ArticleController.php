<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleComment;
use App\Form\ArticleCommentType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/", name="article_show", methods={"GET","POST"})
     */
    public function show($id, Article $article): Response
    {
        $form = $this->createForm(ArticleCommentType::class, new ArticleComment(), [
            'action' => $this->generateUrl('article_add_comment', ['id' => $id]),
            'method' => 'POST',
            'attr'   => ['id' => 'comment_form']
        ]);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $article->getComments(),
            'commentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/new-comment/", name="article_add_comment", methods={"POST"})
     */
    public function addComment(Article $article, Request $request): Response
    {
        $articleComment = new ArticleComment();

        $articleComment
            ->setOwner($article)
            ->setAuthor($request->get('author'))
            ->setContent($request->get('content'))
        ;

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($articleComment);
        $entityManager->flush();

        return new JsonResponse($articleComment->toArray());
    }

    /**
     * @Route("/{id}/edit/", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article, FileUploader $fileUploader): Response
    {
        if (!empty($article->getFile())){
            $article->setFile(
                new File($this->getParameter('article_file_directory') . '/' . $article->getFile())
            );
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }

}
