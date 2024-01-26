<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


Class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index():Response
    {
        return $this->render('pages/accueil.html.twig');
    }
}