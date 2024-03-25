<?php

namespace App\Controller;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UsersRepository;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\SendMailService;
// use Symfony\Component\Security\Core\Encoder\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('alert', 'Déjà connecté(e) !');
            return $this->redirectToRoute('app_accueil');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // vérification si idd incorrect
        if( $error != null)
        {
            $this->addFlash('error', 'Vous n\'avez pas l\'authorisation d\'accéder à cette partie du site !');
        } 

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/mot-de-passe-oublie', name: 'app_forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UsersRepository $usersRepository, 
        TokenGeneratorInterface $tokenGeneratorInterface, 
        EntityManagerInterface $entityManager, 
        SendMailService $mail
        ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on va chercher l'utilisateur par son email
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            // on evérifie si l'utilisateur existe
            if($user){
                // on génère un token de réinitialisation de mot de passe
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // on génère un lien de réinitialisation de mot de passe
                $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                
                // On crée les données mail
                $context = compact('url', 'user');

                //Envoi du mail
                $mail->sendMail(
                    'info@rgraphic.fr',
                    $user->getEmail(),
                    'Réinitialisation de votre mot de passe',
                    'password_reset',
                    $context
                );

                $this->addFlash('success', 'Un email de réinitialisation de mot de passe vous a été envoyé.');
                return $this->redirectToRoute('app_login');
            }
            // $user est null
            $this->addFlash('error', 'Un problème est survenu.');
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('security/reset_password_request.html.twig',
            ['requestPassForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/reinitialisation-mot-de-passe/{token}', name: 'app_reset_password')]
    public function resetPass(
        string $token,
        Request $request,
        UsersRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ): Response
    {
        // On vérifie si on a ce token dans la bdd
        $user = $usersRepository->findOneByResetToken($token);
        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $userPasswordHasherInterface->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été réinitialisé.');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'resetPassForm' => $form->createView(),
            ]);
        }
        $this->addFlash('error', 'Jeton inconnu');
        return $this->redirectToRoute('app_login');
    }
}
