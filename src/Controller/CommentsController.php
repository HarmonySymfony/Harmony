<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('')]
class CommentsController extends AbstractController
{
    #[Route('/backoffice/{postId}/comments/list', name: 'app_comments_index', methods: ['GET'])]
    public function index(CommentsRepository $commentsRepository, EntityManagerInterface $entityManager, $postId): Response
    {
        // Retrieve the post by postId
        $post = $entityManager->getRepository(Posts::class)->find($postId);

        // Check if the post exists
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        // Retrieve comments related to the post
        $comments = $commentsRepository->findBy(['post' => $post]);

        // Render the template, passing comments and postId as variables
        return $this->render('backoffice/comments/index.html.twig', [
            'comments' => $comments,
            'postId' => $postId,
        ]);
    }

    #[Route('/backoffice/{postId}/comments/add', name: 'app_comments_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $postId): Response
    {
        $post = $entityManager->getRepository(Posts::class)->find($postId);


        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $comment = new Comments();

        // Get the current user ID
        $user = $this->getUser();

        // Set the user ID on the Posts entity
        $comment->setUtilisateur($user);

        // Set the post ID on the Comments entity
        $comment->setPost($post);

        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_comments_index',  ['postId' => $postId], Response::HTTP_SEE_OTHER);
        }

        // Pass the postId parameter to the template when rendering
        return $this->renderForm('backoffice/comments/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'postId' => $postId, // Pass postId to the template
        ]);
    }

    #[Route('/backoffice/{postId}/comments/read/{id}', name: 'app_comments_show', methods: ['GET'])]
    public function show(Comments $comment, $postId): Response
    {
        return $this->render('backoffice/comments/show.html.twig', [
            'comment' => $comment,
            'postId' => $postId,
        ]);
    }

    #[Route('/backoffice/{postId}/comments/edit/{id}', name: 'app_comments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comments $comment, EntityManagerInterface $entityManager, $postId): Response
    {
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comments_index', ['postId' => $postId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/comments/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'postId' => $postId,
        ]);
    }

    #[Route('/backoffice/{postId}/comments/delete/{id}', name: 'app_comments_delete', methods: ['POST'])]
    public function delete(Request $request, Comments $comment, EntityManagerInterface $entityManager, $postId): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comments_index', ['postId' => $postId], Response::HTTP_SEE_OTHER);
    }
//----------------------------------------------------
//---------------------------------------------------------
//----------------------------------------------------------
//FRONT

    #[Route('/frontoffice/{postId}/comments/list', name: 'app_comments_index_F', methods: ['GET'])]
    public function index_F(CommentsRepository $commentsRepository, EntityManagerInterface $entityManager, $postId): Response
    {
        // Retrieve the post by postId
        $post = $entityManager->getRepository(Posts::class)->find($postId);

        // Check if the post exists
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        // Retrieve comments related to the post
        $comments = $commentsRepository->findBy(['post' => $post]);

        // Render the template, passing comments and postId as variables
        return $this->render('frontoffice/comments/index.html.twig', [
            'comments' => $comments,
            'postId' => $postId,
        ]);
    }

    #[Route('/frontoffice/{postId}/comments/add', name: 'app_comments_new_F', methods: ['GET', 'POST'])]
    public function new_F(Request $request, EntityManagerInterface $entityManager, $postId): Response
    {
        $post = $entityManager->getRepository(Posts::class)->find($postId);


        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $comment = new Comments();

        // Get the current user ID
        $user = $this->getUser();

        // Set the user ID on the Posts entity
        $comment->setUtilisateur($user);

        // Set the post ID on the Comments entity
        $comment->setPost($post);

        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_comments_index_F',  ['postId' => $postId], Response::HTTP_SEE_OTHER);
        }

        // Pass the postId parameter to the template when rendering
        return $this->renderForm('frontoffice/comments/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'postId' => $postId, // Pass postId to the template
        ]);
    }

    #[Route('/frontoffice/{postId}/comments/read/{id}', name: 'app_comments_show_F', methods: ['GET'])]
    public function show_F(Comments $comment, $postId): Response
    {
        return $this->render('frontoffice/comments/show.html.twig', [
            'comment' => $comment,
            'postId' => $postId,
        ]);
    }

    #[Route('/frontoffice/{postId}/comments/edit/{id}', name: 'app_comments_edit_F', methods: ['GET', 'POST'])]
    public function edit_F(Request $request, Comments $comment, EntityManagerInterface $entityManager, $postId): Response
    {
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comments_index_F', ['postId' => $postId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontoffice/comments/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'postId' => $postId,
        ]);
    }

    #[Route('/frontoffice/{postId}/comments/delete/{id}', name: 'app_comments_delete_F', methods: ['POST'])]
    public function delete_F(Request $request, Comments $comment, EntityManagerInterface $entityManager, $postId): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comments_index', ['postId' => $postId], Response::HTTP_SEE_OTHER);
    }

}
