<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Services;
use App\Form\AddcontentFormType;
use App\Entity\Parametre;
use App\Entity\Membre;
use App\Entity\Categories;
use App\Form\LogoAccueilFormType;
use App\Form\TextAccueilFromType;
use App\Form\InfoContactFormType;
use App\Form\AjoutMembreFormType;
use App\Form\ModifMembreFormType;
use App\Form\SelectMembreFormType;
use App\Form\SupprimerMembreFormType;
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
            // on récupère le name du bouton pressé
            $buttonpressed = $request->request->all();

            // on parse le tableau pour récupérer le name du bouton pressé
            $buttonpressed = array_keys($buttonpressed);
            $button = $buttonpressed[1];
            // Convertir en chaine de caractères
            $button = (string)$button;

            $button = trim($button);



            if($button == "add"){

                // Si l'image ne fais pas plus de 200ko
                if($service->getImageFile()->getSize() < 200000){

                    // si il n'y a pas de caractères spéciaux ( injection de code, etc...)
                    if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $service->getLegende()) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $service->getTitre())){
                        $this->addFlash('error', 'Les caractères spéciaux ne sont pas autorisés.');
                        return $this->redirectToRoute('app_administration');
                    }
                    
                    // On ajoute les données dans la base de données avec try catch
                    try{
                        $entityManager->persist($service);
                        $entityManager->flush();
                        $this->addFlash('success', 'L\'image et ses infrmations ont bien été ajoutés.');
                        return $this->redirectToRoute('app_administration');
                    } catch(\Exception $e){
                        $this->addFlash('error', 'Une erreur est survenue, veuillez réessayer.');
                        return $this->redirectToRoute('app_administration');
                    }
                }else{
                    $this->addFlash('error', 'L\'image est trop lourde, elle ne doit pas dépasser 200ko.');

                }

            } elseif ( $button == 'delete' ){
                // à partir du titre de la légende et du select on récupère le service à supprimer
                $serviceAsupp = $entityManager->getRepository(Services::class)->findOneBy(['titre' => $service->getTitre(), 'legende' => $service->getLegende(), 'categorie' => $service->getCategorie()]);
                // si le service existe
                if($serviceAsupp != null){
                    // On supprime le service
                    $entityManager->remove($serviceAsupp);
                    $entityManager->flush();
                    $this->addFlash('success', 'Le service a bien été supprimé.');
                    return $this->redirectToRoute('app_administration');
                }else{
                    $this->addFlash('error', 'Le service n\'existe pas, veuillez essayer à nouveau.');
                }
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

            // on récupère toutes les données du formulaire
            $logo = $formLogo->getData();
            $nom_param = 'LOGO';
            $logo->setNomParam($nom_param);
            $entityManager->persist($logo);
            $entityManager->flush();
            $this->addFlash('success', 'Le logo a bien été modifié.');
            return $this->redirectToRoute('app_administration');
        }


        // Formulaire de modification du texte d'accueil

        $textAccueil = new Parametre();
        $formTextAccueil = $this->createForm(TextAccueilFromType::class, $textAccueil);
        $formTextAccueil->handleRequest($request);

        if($formTextAccueil->isSubmitted() && $formTextAccueil->isValid()){
            //suppression de l'ancien texte
            $textAccueil = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'TEXTE_ACCUEIL']);
            if($textAccueil != null){
                $entityManager->remove($textAccueil);
                $entityManager->flush();
            }
           

            // on récupère toutes les données du formulaire
            $textAccueil = $formTextAccueil->getData();
            $nom_param = 'TEXTE_ACCUEIL';
            $textAccueil->setNomParam($nom_param);
            $entityManager->persist($textAccueil);
            $entityManager->flush();
            $this->addFlash('success', 'Le texte d\'accueil a bien été modifié.');
            return $this->redirectToRoute('app_administration');
        }






        // Formulaire de modification des informations de contact
        $infoContact = new Parametre();
        $formInfoContact = $this->createForm(InfoContactFormType::class, $infoContact);
        $formInfoContact->handleRequest($request);

        if($formInfoContact->isSubmitted() && $formInfoContact->isValid()){
            //suppression de l'information de contact sélectionnée avec la liste déroulante
            $paramseltioned = $formInfoContact->get('nom_param')->getData();
            if($paramseltioned != null){
                $entityManager->remove($paramseltioned);
                $entityManager->flush();
            }
           

            // on récupère toutes les données du formulaire
            $infoContact = $formInfoContact->getData();
            $nom_param = $formInfoContact->get('nom_param')->getData();
            $infoContact->setNomParam($nom_param);
            $entityManager->persist($infoContact);
            $entityManager->flush();
            $this->addFlash('success', 'Les informations de contact ont bien été modifiées.');
            return $this->redirectToRoute('app_administration');
        }





        // traitement du formulaire d'ajout de membres
        $membre = new Membre();
        $formMembre = $this->createForm(AjoutMembreFormType::class, $membre);
        $formMembre->handleRequest($request);

        if($formMembre->isSubmitted() && $formMembre->isValid()){
            // on récupère toutes les données du formulaire
            $membre = $formMembre->getData();

            // Si l'image ne fais pas plus de 200ko et si elle est bien orientée au format portrait
            if($membre->getImageFile()->getSize() < 200000){ // 1 = portrait 0 = paysage 2 = carré 3 = inversé portrait 4 = inversé paysage 5 = inversé carré

                // si il n'y a pas de caractères spéciaux ( injection de code, etc...)
                if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $membre->getNom()) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $membre->getPrenom()) || preg_match('/[\^£$%&*()}{@#~><>|=_+¬-]/', $membre->getDescription()) || preg_match('/[\^£$%&*()}{@#~><>|=_+¬-]/', $membre->getRole()) ){
                    $this->addFlash('error', 'Les caractères spéciaux ne sont pas autorisés.');
                    return $this->redirectToRoute('app_administration');
                }
                

                // On ajoute les données dans la base de données
                // exepetion si flush ne se passe pas bien
                try{
                    $entityManager->persist($membre);
                    $entityManager->flush();
                } catch(\Exception $e){
                    $this->addFlash('error', 'Une erreur est survenue, veuillez réessayer.');
                    return $this->redirectToRoute('app_administration');
                }
                // si flush s'est bien passé, on addflash
                $this->addFlash('success', 'Le membre et ses infrmations ont bien été ajoutés.');
                return $this->redirectToRoute('app_administration');
            }else{
                $this->addFlash('error', 'L\'image est trop lourde, elle ne doit pas dépasser 200ko.');

            }
        }


        // Formulaire de modification des membres
        $membre = new Membre();
        $modifyForm = $this->createForm(ModifMembreFormType::class, $membre);
        $modifyForm->handleRequest($request);
        if($modifyForm->isSubmitted() && $modifyForm->isValid()){
            // on récupère toutes les données du formulaire
            $membre = $modifyForm->getData();
            // on récupère le membre à modifier
            $membreamodif = $entityManager->getRepository(Membre::class)->findOneBy(['prenom' => $membre->getPrenom(), 'nom' => $membre->getNom()]);
            // si le membre existe
            if($membreamodif != null){
                // si l'image ne fais pas plus de 200ko
                // if($membre->getImageFile()->getSize() < 200000 && $membre->getImageFile() != null){
                    // si il n'y a pas de caractères spéciaux ( injection de code, etc...)
                    if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $membre->getNom()) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $membre->getPrenom()) || preg_match('/[\^£$%&*()}{@#~><>|=_+¬-]/', $membre->getDescription()) || preg_match('/[\^£$%&*()}{@#~><>|=_+¬-]/', $membre->getRole()) ){
                        $this->addFlash('error', 'Les caractères spéciaux ne sont pas autorisés.');
                        return $this->redirectToRoute('app_administration');
                    }

                    // exepetion si flush ne se passe pas bien
                    // On remplace les données du membre à modifier par les nouvelles données
                    
                    if($membre->getNom() != null){
                        $membreamodif->setNom($membre->getNom());
                    }
                    if($membre->getPrenom() != null){
                        $membreamodif->setPrenom($membre->getPrenom());
                    }
                    if($membre->getDescription() != null){
                        $membreamodif->setDescription($membre->getDescription());
                    }
                    if($membre->getRole() != null){
                        $membreamodif->setRole($membre->getRole());
                    }                    
                    if( $membre->getImageFile() != null){
                        $image = $membre->getImageFile();
                        if($membre->getImageFile()->getSize() < 200000){
                            $membreamodif->setImageFile($membre->getImageFile());
                        }
                    }
                    // On ajoute les données dans la base de données
                    try{
                        $entityManager->persist($membreamodif);
                        $entityManager->flush();
                    } catch(\Exception $e){
                        $this->addFlash('error', 'Une erreur est survenue, veuillez réessayer.');
                        return $this->redirectToRoute('app_administration');
                    }
                    // si flush s'est bien passé, on addflash
                    $this->addFlash('success', 'Le membre et ses infrmations ont bien été modifiés.');
                    return $this->redirectToRoute('app_administration');
                // }else{
                //     $this->addFlash('error', 'L\'image est trop lourde, elle ne doit pas dépasser 200ko.');
                // }
            }else{
                $this->addFlash('error', 'Le membre n\'existe pas.');
            }
        }

        // Formulaire de suppression de membres
        $membresupp = new Membre();
        $supprimerForm = $this->createForm(SupprimerMembreFormType::class, $membresupp);
        $supprimerForm->handleRequest($request);
        if($supprimerForm->isSubmitted() && $supprimerForm->isValid()){
            // on récupère la donné de la liste déroulante
            $prenom = $supprimerForm->get('prenom')->getData();
            // dd($prenom);
            // si le membre existe
            if($prenom != null){
                
                // on cherche la correspondance dans la base de données
                $membreAsupp = $entityManager->getRepository(Membre::class)->findOneBy(['prenom' => $prenom->getPrenom()]);
                // dd($membreAsupp);
                if( $membreAsupp != null){
                    // On supprime le membre
                    $entityManager->remove($membreAsupp);
                    $entityManager->flush();
                    $this->addFlash('success', 'Le membre a bien été supprimé.');
                    return $this->redirectToRoute('app_administration');
                }
            }else{
                $this->addFlash('error', 'Le membre n\'existe pas.');
            }
        }


        // on récupère le valu_param du nom_param sélectionné depuis la liste déroulante du formulaire de modification des informations de contact
        $infoscontact = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => $formInfoContact->get('nom_param')->getData()]);

        // On récupère le logo de la page d'accueil        
        $parametrelogoAccueil = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'LOGO']);

        // On récupère le texte d'accueil
        $parametreTextAccueil = $entityManager->getRepository(Parametre::class)->findOneBy(['nom_param' => 'TEXTE_ACCUEIL']);
        // s'il n'existe pas, on affiche "aucun texte d'accueil pour le moment, veuillez en ajouter un."
        if($parametreTextAccueil == null){
            $parametreTextAccueil = new Parametre();
            $parametreTextAccueil->setValueParam('Aucun texte d\'accueil pour le moment, veuillez en ajouter un.');
        }

        return $this->render('administration/index.html.twig', [
            'addContentForm' => $form->createView(),
            'registrationForm' => $formIns->createView(),
            'logoAccueilForm' => $formLogo->createView(),
            'textAccueilForm' => $formTextAccueil->createView(),
            'infoContactForm' => $formInfoContact->createView(),
            'ajoutMembreForm' => $formMembre->createView(),
            'supprimerMembreForm' => $supprimerForm->createView(),
            // 'selectForm' => $selectForm->createView(),
            'modifyForm' => $modifyForm->createView(),
            'controller_name' => 'AdministrationController',
            'parametrelogoAccueil' => $parametrelogoAccueil,
            'parametreTextAccueil' => $parametreTextAccueil,
            'infoscontact' => $infoscontact,
        ]);
    }
    
}




   
