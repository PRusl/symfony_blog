<?php

namespace App\Controller;

use App\Entity\ArticleComment;
use App\Form\ArticleCommentType;
use App\Repository\ArticleCommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article/comment")
 */
class ArticleCommentController extends AbstractController
{
    /**
     * @Route("/", name="article_comment_index", methods={"GET"})
     */
    public function index(ArticleCommentRepository $articleCommentRepository): Response
    {
        return $this->render('article_comment/index.html.twig', [
            'article_comments' => $articleCommentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="article_comment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $articleComment = new ArticleComment();
        $form = $this->createForm(ArticleCommentType::class, $articleComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($articleComment);
            $entityManager->flush();

            return $this->redirectToRoute('article_comment_index');
        }

        return $this->render('article_comment/new.html.twig', [
            'article_comment' => $articleComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_comment_show", methods={"GET"})
     */
    public function show(ArticleComment $articleComment): Response
    {
        return $this->render('article_comment/show.html.twig', [
            'article_comment' => $articleComment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ArticleComment $articleComment): Response
    {
        $form = $this->createForm(ArticleCommentType::class, $articleComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_comment_index', [
                'id' => $articleComment->getId(),
            ]);
        }

        return $this->render('article_comment/edit.html.twig', [
            'article_comment' => $articleComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ArticleComment $articleComment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articleComment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($articleComment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_comment_index');
    }
}
