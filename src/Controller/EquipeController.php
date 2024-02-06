<?php

namespace App\Controller;


use App\Entity\Membre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SelectMembreFormType;


class EquipeController extends AbstractController
{
    #[Route('/equipe', name: 'app_equipe')]
    public function index(
        EntityManagerInterface $entityManager,
        Response $response,
        Request $request
    ): Response
    {
        // Formulaire de selection du membre 
        $select = $this->createForm(SelectMembreFormType::class);
        $select->handleRequest($request);



        // On récupère ligne par ligne les membres de l'équipe
        $membres = $entityManager->getRepository(Membre::class)->findAll();

        // For($i=0; $i<count($membres); $i++){
        //     dd($membres[$i]);
        // }

        // dd($membres);


        return $this->render('pages/equipe.html.twig', [
            'controller_name' => 'EquipeController',
            'membres' => $membres
        ]);
    }
}
