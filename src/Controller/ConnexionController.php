<?php

namespace App\Controller;

use App\Form\ConnexionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'app_connexion')]
    public function index(Request $request): Response
    {

        $form = $this->createForm(ConnexionType::class);






        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
            'form' => $form->createView(),
        ]);
    }
}
