<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Entity\Parametre;
use App\Entity\Services;
use App\Entity\Categories;
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

        $parametrelogoAccueilblack = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'LOGO-BLACK-GR']);
        $parametrelogoAccueilwhite = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'LOGO-WHITE-GR']);
        $parametreTextAccueil = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'TEXTE_ACCUEIL']);
        // récupération des sevices qui coreespondent aux coverings
        $covering = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'TOTAL COVERING'])]);

        // récupération des sevices qui coreespondent aux marquages véhicules
        $marquageVehicule = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'MARQUAGE V'])]);

        // récupération des sevices qui coreespondent aux enseignes
        $enseigne = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'ENSEIGNES'])]);

        // récupération des sevices qui coreespondent aux Logo
        $logocharte = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'LOGOS & CHARTES'])]);

        // récupération des sevices qui coreespondent aux Impression num
        $impressionNum = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'IMPRESSION NUM'])]);

        // récupération des sevices qui coreespondent aux Panneaux
        $panneaux = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'PANNEAUX'])]);

        // récupération des sevices qui coreespondent aux Vitrines
        $vitrines = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'VITRINES'])]);

        // récupération des sevices qui coreespondent aux Bannieres
        $bannieres = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'BANNIERES'])]);

        // récupération des sevices qui coreespondent aux EVENEMENTIEL
        $evenementiel = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'EVENEMENTIEL'])]);

        // récupération des sevices qui coreespondent aux IMPRIMERIE
        $imprimerie = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'IMPRIMERIE'])]);

        // récupération des sevices qui coreespondent aux TEXTILES
        $textiles = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'TEXTILES'])]);

        // récupération des sevices qui coreespondent aux GOODIES
        $goodies = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'GOODIES'])]);

        // récupération des sevices qui coreespondent aux PARTENAIRE
        $partenaire = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'PARTENAIRES'])]);

        return $this->render('pages/accueil.html.twig',
        [
            'parametrelogoAccueilblack' => $parametrelogoAccueilblack,
            'parametrelogoAccueilwhite' => $parametrelogoAccueilwhite,
            'parametreTextAccueil' => $parametreTextAccueil,
            'covering' => $covering,
            'marquageVehicule' => $marquageVehicule,
            'enseigne' => $enseigne,
            'logocharte' => $logocharte,
            'impressionNum' => $impressionNum,
            'panneaux' => $panneaux,
            'vitrines' => $vitrines,
            'bannieres' => $bannieres,
            'evenementiel' => $evenementiel,
            'imprimerie' => $imprimerie,
            'textiles' => $textiles,
            'goodies' => $goodies,
            'partenaire' => $partenaire
        ]);
    }
}