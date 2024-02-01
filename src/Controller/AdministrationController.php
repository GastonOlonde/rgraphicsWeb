<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Services;
use App\Entity\Categories;
use App\Form\AddcontentFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{
    #[Route('/administration', name: 'app_administration')]
    public function addContent(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        // on créer un nouveau service
        $service = new Services();
        $form = $this->createForm(AddcontentFormType::class, $service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // on récupère toutes les données du formulaire
            $service = $form->getData();
            // On ajoute les données dans la base de données
            $entityManager->persist($service);
            $entityManager->flush();
            // si flush s'est bien passé, on addflash
            $this->addFlash('success', 'Votre service a bien été ajouté.');
        }

        if(!$this->getUser())
        {
            $this->addFlash('error', 'Vous n\'avez pas accès à cette page.');
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('administration/index.html.twig', [
            'addContentForm' => $form->createView(),
            'controller_name' => 'AdministrationController',
        ]);
    }
}




   
