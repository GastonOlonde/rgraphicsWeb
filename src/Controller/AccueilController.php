<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Entity\Parametre;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;


Class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(
        EntityManagerInterface $entityManager
    ):Response
    {

        $parametrelogoAccueil = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'LOGO']);
        $parametreTextAccueil = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'TEXTE_ACCUEIL']);

        return $this->render('pages/accueil.html.twig',
        [
            'parametrelogoAccueil' => $parametrelogoAccueil,
            'parametreTextAccueil' => $parametreTextAccueil
        ]);
    }
}