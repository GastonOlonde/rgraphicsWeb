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
        $covering = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'COVERING'])]);

        // récupération des sevices qui coreespondent aux marquages véhicules du plus récent au plus ancien
        $marquageVehicule = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'MARQUAGES VEHICULES'])], ['id' => 'DESC']);


        // récupération des sevices qui coreespondent aux enseignes
        $enseigne = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'ENSEIGNES-VITRINES-PANNEAUX'])]);
        $nombreDeResultat = count($enseigne);
        $moitié = floor($nombreDeResultat / 2);

        $enseigneP1 = array_slice($enseigne, 0, $moitié);

        $enseigneP2 = array_slice($enseigne, $moitié);

        // récupération des sevices qui coreespondent aux Logo
        $logocharte = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'LOGOS & CHARTES GRAPHIQUES'])], ['id' => 'DESC']);

        // récupération des sevices qui coreespondent aux Impression num
        $impressionNum = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'IMPRESSION NUMERIQUE'])]);

        // récupération des sevices qui coreespondent aux IMPRIMERIE
        $imprimerie = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'IMPRIMERIE-EVENEMENTIEL-BANDEROLES'])]);

        // récupération des sevices qui coreespondent aux TEXTILES
        $textiles = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'TEXTILES'])], ['id' => 'DESC']);

        // récupération des sevices qui coreespondent aux GOODIES
        $goodies = $entityManager->getRepository(Services::class)->findBy(['categorie' => $entityManager->getRepository(Categories::class)->findOneBy(['nom' => 'GOODIES'])]);
        $nbgoodies = count($goodies);
        $tiers = floor($nbgoodies / 3);
        $tiers2 = $nbgoodies - $tiers;
        $tiers2 = $tiers + $tiers;

        // répartition des goodies en 3
        $goodiesP1 = array_slice($goodies, 0, $tiers);
        $goodiesP2 = array_slice($goodies, $tiers, $tiers2);
        $goodiesP3 = array_slice($goodies, $tiers2, $nbgoodies);

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
            'goodiesP1' => $goodiesP1,
            'goodiesP2' => $goodiesP2,
            'goodiesP3' => $goodiesP3,
            'partenaire' => $partenaire,
            'parametrePimentBlack' => $parametrePimentBlack,
            'parametrePimentWhite' => $parametrePimentWhite,
        ]);
    }
}