<?php
// src/Controller/ContactController.php
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
use Swift_Message;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        // Création du formulaire
        $form = $this->createForm(ContactFormType::class);

        // Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez les données du formulaire (par exemple, envoyez un e-mail)
            $formData = $form->getData();

            // Créez le corps de l'e-mail
            $emailBody = sprintf(
                "Nom: %s\nEmail: %s\nMessage: %s",
                $formData['name'],
                $formData['email'],
                $formData['message']
            );

            // Créez un nouvel objet Swift_Message
            $message = (new Email())
                ->from($formData['email'])
                ->to('votre@email.com')
                ->subject('Nouveau message de contact')
                ->text($emailBody);

            // Envoyez l'e-mail
            $mailer->send($message);

            // Ajoutez ici toute logique supplémentaire (par exemple, redirection avec notification)
            $this->addFlash('success', 'Votre message a été envoyé avec succès !');
            return $this->redirectToRoute('contact');
        }

        // Affichage du formulaire dans la page 'pages/contact.html.twig'
        return $this->render('pages/contact.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(), // Passer le formulaire à la vue
        ]);
    }
}
