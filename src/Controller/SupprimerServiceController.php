<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Services;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;


class SupprimerServiceController extends AbstractController
{
    #[Route('/supprimer/service/{id}', name: 'app_supprimer_service', methods: 'DELETE')]
    public function supprimer(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        if($request->isXmlHttpRequest()){
            $id = $request->get('id');
            $service = $entityManager->getRepository(Services::class)->find($id);
            $entityManager->remove($service);
            $entityManager->flush();
            return new JsonResponse(['success' => 'Service supprimé avec succès']);
        }
        else{
            // log
            return new JsonResponse(['error' => 'Erreur lors de la suppression']);  
        }    
    }
}
