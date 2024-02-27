<?php
namespace App\ViewComposer;

use App\Service\HeaderDataService;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Symfony\Component\VarDumper\VarDumper;

class HeaderViewComposer
{
    private $headerDataService;
    private $twig;
    private $requestStack;

    public function __construct(HeaderDataService $headerDataService, Environment $twig, RequestStack $requestStack)
    {
        $this->headerDataService = $headerDataService;
        $this->twig = $twig;
        $this->requestStack = $requestStack;
    }

    public function compose(): void
    {

        VarDumper::dump('HeaderViewComposer compose method called');

        // Récupérez les données de l'entité depuis le service
        $headerData = $this->headerDataService->getHeaderData();

        // Ajoutez les données à la vue du header
        $this->twig->addGlobal('headerData', $headerData);

    }
}
