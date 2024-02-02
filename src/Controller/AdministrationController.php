<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Services;
use App\Form\AddcontentFormType;
use App\Entity\Parametre;
use App\Entity\Categories;
use App\Form\LogoAccueilFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\SendMailService;

class AdministrationController extends AbstractController
{
    #[Route('/administration', name: 'app_administration')]
    public function addContent(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher, 
        UserAuthenticatorInterface $userAuthenticator, 
        UsersAuthenticator $authenticator, 
        SendMailService $mail
    ): Response
    {
        // on créer un nouveau service
        $service = new Services();
        $form = $this->createForm(AddcontentFormType::class, $service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // on récupère toutes les données du formulaire
            $service = $form->getData();

            // Si l'image ne fais pas plus de 200ko
            if($service->getImageFile()->getSize() < 200000){

                // si il n'y a pas de caractères spéciaux ( injection de code, etc...)
                if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $service->getLegende()) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $service->getTitre())){
                    $this->addFlash('error', 'Les caractères spéciaux ne sont pas autorisés.');
                    return $this->redirectToRoute('app_administration');
                }
                

                // On ajoute les données dans la base de données
                $entityManager->persist($service);
                // si flush s'est bien passé, on addflash
                if($entityManager->flush()){
                    $this->addFlash('success', 'l\image et ses infrmations ont bien été ajoutés.');
                    return $this->redirectToRoute('app_administration');
                }
            }else{
                $this->addFlash('error', 'L\'image est trop lourde, elle ne doit pas dépasser 200ko.');

            }
        }
        if(!$this->getUser())
        {
            $this->addFlash('error', 'Vous n\'avez pas accès à cette page.');
            return $this->redirectToRoute('app_accueil');
        }



        $user = new Users();
        $formIns = $this->createForm(RegistrationFormType::class, $user);
        $formIns->handleRequest($request);

        if ($formIns->isSubmitted() && $formIns->isValid()) {

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $formIns->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // On envoi un mail
            $mail->sendMail(
                'gastonolonde@gmail.com',
                $user->getEmail(),
                'Bienvenue sur le site en tant qu\'administrateur',
                'register',
                [
                    'user' => $user
                ]
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }


        // Formulaire de modification du grand logo de la page d'accueil
        $logo = new Parametre();
        $formLogo = $this->createForm(LogoAccueilFormType::class, $logo);
        $formLogo->handleRequest($request);

        if($formLogo->isSubmitted() && $formLogo->isValid()){
            //suppression de l'ancien logo
            $logo = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'LOGO']);
            $entityManager->remove($logo);
            $entityManager->flush();

            // instance de parametre

            // on récupère toutes les données du formulaire
            $logo = $formLogo->getData();
            $nom_param = 'LOGO';
            $logo->setNomParam($nom_param);
            $entityManager->persist($logo);
            $entityManager->flush();
            $this->addFlash('success', 'Le logo a bien été modifié.');
            return $this->redirectToRoute('app_administration');
        }


        $parametre = new Parametre();

        return $this->render('administration/index.html.twig', [
            'addContentForm' => $form->createView(),
            'registrationForm' => $formIns->createView(),
            'logoAccueilForm' => $formLogo->createView(),
            'controller_name' => 'AdministrationController',
            'parametre' => $parametre,
        ]);
    }
    
}




   
