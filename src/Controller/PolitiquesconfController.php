<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PolitiquesconfController extends AbstractController
{
    #[Route('/politiquesconf', name: 'app_politiquesconf')]
    public function index(): Response
    {
        return $this->render('politiquesconf/index.html.twig', [
            'controller_name' => 'PolitiquesconfController',
        ]);
    }
}
