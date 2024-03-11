<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Service;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class SupprimerServiceController extends AbstractController
{
    #[Route('/supprimer/service', name: 'app_supprimer_service', methods: 'DELETE')]
    public function supprimer(Service $service): Response
    {
            
        dd($service->getId());

        // Supprimez l'entité du service
        $entityManager->remove($service);
        $entityManager->flush();

        return new Response('Le service a été supprimé avec succès.', Response::HTTP_OK);

    }
}
