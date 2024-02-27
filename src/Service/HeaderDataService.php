<?php
namespace App\Service;
use App\Entity\Parametre;
use Doctrine\ORM\EntityManagerInterface;

class HeaderDataService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getHeaderData()
    {
        // On récupère le logo du header
        $parametrelogoHeaderblack = $this->entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'LOGO-BLACK-PT']);
        $parametrelogoHeaderwhite = $this->entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'LOGO-WHITE-PT']);

        return [
            'parametrelogoHeaderblack' => $parametrelogoHeaderblack,
            'parametrelogoHeaderwhite' => $parametrelogoHeaderwhite,
        ];
    }
}