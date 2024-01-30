<?php
namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\s;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Flashy\FlashyNotifier;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            $email = (new Email())
                ->from($formData['email'])
                ->to('gastonolonde@gmail.com')
                ->subject($formData["objet"])
                ->text($formData["message"]);

            
            if($mailer->send($email)){
               $this->addFlash('success', 'Votre message a bien été envoyé');
            } else {
               $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre message');
            }
        }

        return $this->render('pages/contact.html.twig', [
            'controller_name' => 'ContactController',
            'formulaire' => $form->createView(),
        ]);
    }
}
