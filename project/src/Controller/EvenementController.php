<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }


    #[Route('/evenement-post', name: 'app_evenement_post')]
    public function post(): Response
    {
        return $this->render('evenement/details.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
}
