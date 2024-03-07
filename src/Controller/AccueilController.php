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
        $marquageVehicule = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'MARQUAGE VEHICULES'])]);

        // récupération des sevices qui coreespondent aux enseignes
        $enseigne = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'ENSEIGNES-VITRINES-PANNEAUX'])]);
        $nombreDeResultat = count($enseigne);
        $moitié = floor($nombreDeResultat / 2);

        $enseigneP1 = array_slice($enseigne, 0, $moitié);

        $enseigneP2 = array_slice($enseigne, $moitié);

        // récupération des sevices qui coreespondent aux Logo
        $logocharte = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'LOGOS & CHARTES GRAPHIQUES'])]);

        // récupération des sevices qui coreespondent aux Impression num
        $impressionNum = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'IMPRESSION NUMERIQUE'])]);

        // récupération des sevices qui coreespondent aux IMPRIMERIE
        $imprimerie = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'IMPRIMERIE-EVENEMENTIEL-BANDEROLES'])]);

        // récupération des sevices qui coreespondent aux TEXTILES
        $textiles = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'TEXTILES'])]);

        // récupération des sevices qui coreespondent aux GOODIES
        $goodies = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'GOODIES'])]);

        // récupération des sevices qui coreespondent aux PARTENAIRE
        $partenaire = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'PARTENAIRES'])]);

        // récupération du slogan avec PIMENT-BLACK et PIMENT-WHITE
        $parametrePimentBlack = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'PIMENT-BLACK']);
        $parametrePimentWhite = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'PIMENT-WHITE']);

        return $this->render('pages/accueil.html.twig',
        [
            'parametrelogoAccueilblack' => $parametrelogoAccueilblack,
            'parametrelogoAccueilwhite' => $parametrelogoAccueilwhite,
            'parametreTextAccueil' => $parametreTextAccueil,
            'covering' => $covering,
            'marquageVehicule' => $marquageVehicule,
            'enseigneP1' => $enseigneP1,
            'enseigneP2' => $enseigneP2,
            'enseigne' => $enseigne,
            'logocharte' => $logocharte,
            'impressionNum' => $impressionNum,
            'imprimerie' => $imprimerie,
            'textiles' => $textiles,
            'goodies' => $goodies,
            'partenaire' => $partenaire,
            'parametrePimentBlack' => $parametrePimentBlack,
            'parametrePimentWhite' => $parametrePimentWhite,
        ]);
    }
}