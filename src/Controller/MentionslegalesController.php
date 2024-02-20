<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Parametre;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MentionslegalesController extends AbstractController
{
    #[Route('/mentionslegales', name: 'app_mentionslegales')]
    public function index(
        EntityManagerInterface $entityManager
    ): Response
    {
        // récupération des paramètres tel1 et tel2
        $tel1 = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'TEL1']);
        $tel2 = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'TEL2']);
        // email
        $email = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'MAIL']);
        return $this->render('mentionslegales/index.html.twig', [
            'controller_name' => 'MentionslegalesController',
            'tel1' => $tel1,
            'tel2' => $tel2,
            'email' => $email
        ]);
    }
}
