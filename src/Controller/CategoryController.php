<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryComment;
use App\Form\CategoryCommentType;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/", name="category_show", methods={"GET", "POST"})
     */
    public function show($id, Category $category): Response
    {
        $form = $this->createForm(CategoryCommentType::class, new CategoryComment(), [
            'action' => $this->generateUrl('category_add_comment', ['id' => $id]),
            'method' => 'POST',
            'attr'   => ['id' => 'comment_form']
        ]);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'comments' => $category->getComments(),
            'commentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/new-comment/", name="category_add_comment", methods={"POST"})
     */
    public function addComment(Category $category, Request $request): Response
    {
        $categoryComment = new CategoryComment();

        $categoryComment
            ->setOwner($category)
            ->setAuthor($request->get('author'))
            ->setContent($request->get('content'))
        ;

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($categoryComment);
        $entityManager->flush();

        return new JsonResponse($categoryComment->toArray());
    }

    /**
     * @Route("/{id}/edit/", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index', [
                'id' => $category->getId(),
            ]);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
