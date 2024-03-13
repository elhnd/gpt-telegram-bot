<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant')]
    public function index(): Response
    {
        return $this->render('etudiant/form.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }

    #[Route('/profile', name: 'app_etudiant_profile')]
    public function profile(): Response
    {
        return $this->render('etudiant/profile.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }

    #[Route('/liste', name: 'app_etudiant_liste')]
    public function liste(): Response
    {
        return $this->render('etudiant/liste.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }
}
