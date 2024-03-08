<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/home', name: 'app_hello')]
    public function index(): Response
    {
        return $this->render('frontoffice/index.html.twig', [
            'controller_name' => 'HelloController',
        ]);
    }
    #[Route('/list_users_front', name: 'app_list_users')]
    public function liste_users_front(): Response
    {
        return $this->render('user/liste_users_front.html.twig', [
            'controller_name' => 'HelloController',
        ]);
    }
    #[Route('/hello1', name: 'app_hello1')]
    public function index1(): Response
    {
        return $this->render('hello/index1.html.twig', [
            'controller_name' => 'HelloController',
        ]);
    }

}
