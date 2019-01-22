<?php

namespace App\Controller;

use App\Entity\CategoryComment;
use App\Form\CategoryCommentType;
use App\Repository\CategoryCommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category/comment")
 */
class CategoryCommentController extends AbstractController
{
    /**
     * @Route("/", name="category_comment_index", methods={"GET"})
     */
    public function index(CategoryCommentRepository $categoryCommentRepository): Response
    {
        return $this->render('category_comment/index.html.twig', [
            'category_comments' => $categoryCommentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="category_comment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categoryComment = new CategoryComment();
        $form = $this->createForm(CategoryCommentType::class, $categoryComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoryComment);
            $entityManager->flush();

            return $this->redirectToRoute('category_comment_index');
        }

        return $this->render('category_comment/new.html.twig', [
            'category_comment' => $categoryComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_comment_show", methods={"GET"})
     */
    public function show(CategoryComment $categoryComment): Response
    {
        return $this->render('category_comment/show.html.twig', [
            'category_comment' => $categoryComment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CategoryComment $categoryComment): Response
    {
        $form = $this->createForm(CategoryCommentType::class, $categoryComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_comment_index', [
                'id' => $categoryComment->getId(),
            ]);
        }

        return $this->render('category_comment/edit.html.twig', [
            'category_comment' => $categoryComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CategoryComment $categoryComment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryComment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categoryComment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_comment_index');
    }
}
