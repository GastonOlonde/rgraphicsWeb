<?php
namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\s;
use Symfony\Component\Mime\Address;
use Flashy\FlashyNotifier;
use App\Entity\Parametre;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\mailcontact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Your\FormType\YourCustomFormType;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
    ): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $emailfrom = $formData['email'];
            $nom = $formData['nom'];
            $objet = $formData['objet'];
            $message = $formData['message'];
            $message .= $formData['telephone'];

            $email = (new Email())
                ->from(new Address($emailfrom, $nom))
                ->to('gaston@gmail.com')
                ->subject($objet)
                ->html($message);

            // Envoyer l'email
            $mailer->send($email);
        }

        // on récupère la veleur de nom_param='PDG'
        $infospdg = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'PDG']);
        // on récupère la veleur de nom_param='TEL1'
        $infostel1 = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'TEL1']);
        // on récupère la veleur de nom_param='TEL2'
        $infostel2 = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'TEL2']);
        // on récupère le mail
        $infosmail = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'MAIL']);
        // on récupère l'adresse
        $infosadresse = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'ADRESSE']);
        // on récupère le code postal
        $infoscp = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'CODPOST']);
        // on récupère la ville
        $infosville = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'VILLE']);

        return $this->render('pages/contact.html.twig', [
            'controller_name' => 'ContactController',
            'formulaire' => $form->createView(),
            'infoscontact' => $infospdg,
            'infostel1' => $infostel1,
            'infostel2' => $infostel2,
            'infosmail' => $infosmail,  
            'infosadresse' => $infosadresse,
            'infoscp' => $infoscp,
            'infosville' => $infosville
        ]);
    }
}
