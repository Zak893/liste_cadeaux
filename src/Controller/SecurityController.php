<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\JWTService;
use Doctrine\ORM\EntityManagerInterface;
use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils,SessionInterface $session): Response
    {
        $session->remove('user');
        $session->remove('reset_password_token');
         if ($this->getUser() && $this->getUser()->getUserIdentifier() != null ) {
             return $this->redirectToRoute('profile');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgot-password', name: 'app_forgot_password_request')]
    public function forgotPasswordRequest(Request $request, UserRepository $userRepository, MailerInterface $mailer, JWTService $jwt): Response
    {
        if ($request->isMethod('POST')) {
            // Récupérez l'e-mail de l'utilisateur depuis le formulaire
            $email = $request->request->get('email');
            // Recherchez l'utilisateur par e-mail
            $user = $userRepository->findOneBy(['email' => $email]);
            if ($user) {
                $apikey = '34c0e4be87b475c66927bb120498351e';
                $apisecret = '2dea9bfc2df41294816bcd5bb506c3cf';
                $header = [
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ];

                // On crée le Payload
                $payload = [
                    'user_id' => $user->getId()
                ];
                $token = $jwt->generate($header, $payload, 'Paswword123*');
                $mj = new \Mailjet\Client($apikey, $apisecret,true,['version' => 'v3.1']);
                $url = $this->generateUrl('app_forgot_password_check', ['user' => $user->getId(),'token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                $body = [
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => "zakariaaidouni@gmail.com",
                                'Name' => "Me"
                            ],
                            'To' => [
                                [
                                    'Email' => $user->getEmail(),
                                    'Name' => "You"
                                ]
                            ],
                            'Subject' => "Réinitialisation de votre mot de passe",
                            'TextPart' => "SYMFONY!",
                            'HTMLPart' => "<h3>Réinitialisation de votre mot de passe</h3>
            <p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
            <p><a href=\"" . $url . "\">Réinitialiser le mot de passe</a></p>
            <br />Que la force de livraison soit avec vous !"
                        ]
                    ]
                ];
                $response = $mj->post(Resources::$Email, ['body' => $body]);

                $response->success() && $response->getData();
                // Redirigez l'utilisateur vers une page de confirmation
                return $this->redirectToRoute('app_login');
            }
            return $this->redirectToRoute('app_login');

        }

        return $this->render('security/forgot_password_request.html.twig');
    }
    #[Route('/forgot-password/check', name: 'app_forgot_password_check')]
    public function forgotPasswordCheck(SessionInterface $session,Request $request,JWTService $jwt, UserRepository $usersRepository, EntityManagerInterface $em): Response
    {
        $token = $request->query->get('token'); // Obtenez le jeton depuis la requête
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, "Paswword123*")){
            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $usersRepository->find($payload['user_id']);
            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user){
                $session->set('reset_password_token', $token);
                $session->set('user', $user->getId());
                return $this->redirectToRoute('rest', ['user' => $user->getId(), 'token' => $token]);
            }
        }
        // Ici un problème se pose dans le token
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/forgot-password/rest', name: 'rest')]
    public function rest(Request $request,SessionInterface $session,AuthenticationUtils $authenticationUtils,JWTService $jwt, UserRepository $usersRepository): mixed
    {

        $token = $session->get('reset_password_token');
        $userId = $session->get('user');
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, "Paswword123*")){
            if ($request->isMethod('POST')) {
                $user = $usersRepository->findOneBy(['id' => $userId]);
                $user->setPassword($request->request->get('password'));
                $usersRepository->getEntityManager()->persist($user);
                $usersRepository->getEntityManager()->flush();
                return  $this->redirectToRoute('app_login');
            }
          return  $this->render('security/rest.html.twig');
        }
        return  $this->redirectToRoute('app_login');
    }



}
