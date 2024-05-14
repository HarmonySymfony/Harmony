<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts')]
class PostsController extends AbstractController
{
    #[Route('/backoffice/list', name: 'app_posts_index', methods: ['GET'])]
    public function index(PostsRepository $postsRepository): Response
    {
        return $this->render('backoffice/posts/index.html.twig', [
            'posts' => $postsRepository->findAll(),
        ]);
    }

    #[Route('/backoffice/add', name: 'app_posts_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Posts();


        // Get the current user ID
        $user = $this->getUser();

        // Set the user ID on the Posts entity
        $post->setUtilisateur($user);

        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_posts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/posts/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/backoffice/read/{id}', name: 'app_posts_show', methods: ['GET'])]
    public function show(Posts $post): Response
    {
        return $this->render('backoffice/posts/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/backoffice/edit/{id}', name: 'app_posts_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Posts $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_posts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/posts/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/backoffice/delete/{id}', name: 'app_posts_delete', methods: ['POST'])]
    public function delete(Request $request, Posts $post, EntityManagerInterface $entityManager): Response
    {
        // Check if the CSRF token is valid
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            // Delete the post
            $entityManager->remove($post);
            $entityManager->flush();

            // Redirect to the index page after deletion
            return $this->redirectToRoute('app_posts_index');
        }

        // If CSRF token is invalid, handle the error (e.g., show an error message)
        // Redirect to the index page or display an error message
        return $this->redirectToRoute('app_posts_index');
    }

    #[Route('/backoffice/like/{id}', name: 'app_posts_like', methods: ['POST'])]
    public function likePost(Request $request, Posts $post): Response
    {
        $user = $this->getUser();
        if (!$user) {
            // Handle unauthenticated users
            // Redirect to login page or display an error message
        }

        $likedBy = $post->getLikedBy() ?? [];
        if (!in_array($user->getId(), $likedBy, true)) {
            $likedBy[] = $user->getId();
            $post->setLikedBy($likedBy);
        } else {
            $likedBy = array_diff($likedBy, [$user->getId()]);
            $post->setLikedBy($likedBy);
        }

        // Update the dislikes if necessary
        $dislikedBy = $post->getDislikedBy() ?? [];
        $dislikedBy = array_diff($dislikedBy, [$user->getId()]);
        $post->setDislikedBy($dislikedBy);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_posts_index');
    }


    #[Route('/backoffice/dislike/{id}', name: 'app_posts_dislike', methods: ['POST'])]
    public function dislikePost(Request $request, Posts $post): Response
    {
        $user = $this->getUser();
        if (!$user) {
            // Handle unauthenticated users
            // Redirect to login page or display an error message
        }

        $dislikedBy = $post->getDislikedBy() ?? [];
        if (!in_array($user->getId(), $dislikedBy, true)) {
            $dislikedBy[] = $user->getId();
            $post->setDislikedBy($dislikedBy);
        } else {
            $dislikedBy = array_diff($dislikedBy, [$user->getId()]);
            $post->setDislikedBy($dislikedBy);
        }

        // Update the likes if necessary
        $likedBy = $post->getLikedBy() ?? [];
        $likedBy = array_diff($likedBy, [$user->getId()]);
        $post->setLikedBy($likedBy);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_posts_index');
    }


//    --------------------------------------------------------
//    --------------------------------------------------------
//    --------------------------------------------------------
//    --------------------------------------------------------
//FRONTOFFICE

    #[Route('/frontoffice/list', name: 'app_posts_index_F', methods: ['GET'])]
    public function index_F(PostsRepository $postsRepository): Response
    {
        return $this->render('frontoffice/posts/index.html.twig', [
            'posts' => $postsRepository->findAll(),
        ]);
    }

    #[Route('/frontoffice/add', name: 'app_posts_new_F', methods: ['GET', 'POST'])]
    public function new_F(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Posts();


        // Get the current user ID
        $user = $this->getUser();

        // Set the user ID on the Posts entity
        $post->setUtilisateur($user);

        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_posts_index_F', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontoffice/posts/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/frontoffice/read/{id}', name: 'app_posts_show_F', methods: ['GET'])]
    public function show_F(Posts $post): Response
    {
        return $this->render('frontoffice/posts/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/frontoffice/edit/{id}', name: 'app_posts_edit_F', methods: ['GET', 'POST'])]
    public function edit_F(Request $request, Posts $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_posts_index_F', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontoffice/posts/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/frontoffice/delete/{id}', name: 'app_posts_delete_F', methods: ['POST'])]
    public function delete_F(Request $request, Posts $post, EntityManagerInterface $entityManager): Response
    {
        // Check if the CSRF token is valid
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            // Delete the post
            $entityManager->remove($post);
            $entityManager->flush();

            // Redirect to the index page after deletion
            return $this->redirectToRoute('app_posts_index_F');
        }

        // If CSRF token is invalid, handle the error (e.g., show an error message)
        // Redirect to the index page or display an error message
        return $this->redirectToRoute('app_posts_index_F');
    }

    #[Route('/frontoffice/like/{id}', name: 'app_posts_like_F', methods: ['POST'])]
    public function likePost_F(Request $request, Posts $post): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            // handle the case where there is no user
            throw $this->createNotFoundException('No user is logged in.');
        }
        
        $post->setUtilisateur($user);

        $likedBy = $post->getLikedBy() ?? [];
        if (!in_array($user->getId(), $likedBy, true)) {
            $likedBy[] = $user->getId();
            $post->setLikedBy($likedBy);
        } else {
            $likedBy = array_diff($likedBy, [$user->getId()]);
            $post->setLikedBy($likedBy);
        }

        // Update the dislikes if necessary
        $dislikedBy = $post->getDislikedBy() ?? [];
        $dislikedBy = array_diff($dislikedBy, [$user->getId()]);
        $post->setDislikedBy($dislikedBy);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_posts_index_F');
    }


    #[Route('/frontoffice/dislike/{id}', name: 'app_posts_dislike_F', methods: ['POST'])]
    public function dislikePost_F(Request $request, Posts $post): Response
    {
        $user = $this->getUser();
        if (!$user) {
            // Handle unauthenticated users
            // Redirect to login page or display an error message
        }

        $dislikedBy = $post->getDislikedBy() ?? [];
        if (!in_array($user->getId(), $dislikedBy, true)) {
            $dislikedBy[] = $user->getId();
            $post->setDislikedBy($dislikedBy);
        } else {
            $dislikedBy = array_diff($dislikedBy, [$user->getId()]);
            $post->setDislikedBy($dislikedBy);
        }

        // Update the likes if necessary
        $likedBy = $post->getLikedBy() ?? [];
        $likedBy = array_diff($likedBy, [$user->getId()]);
        $post->setLikedBy($likedBy);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_posts_index_F');
    }


}