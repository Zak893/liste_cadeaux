<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use google\appengine\api\mail\Message;
use \Mailjet\Resources;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends AbstractController
{


    /**
     * @Route("/registration", name="app_register")
     */
    public function index(Request $request, EntityManagerInterface $entityManager,SendMailService $mail, JWTService $jwt)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($user->getPassword());
            // Set their role
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(0);
            // Save
            $entityManager->persist($user);
            $entityManager->flush();

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
            $token = $jwt->generate($header, $payload, 'Azerty123;');
            $mj = new \Mailjet\Client($apikey, $apisecret,true,['version' => 'v3.1']);
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
                        'Subject' => "confirmation de compte!",
                        'TextPart' => "SYMFONY!",
                        'HTMLPart' => "<h3>Dear passenger 1, welcome to our site <a href=" . $this->generateUrl('verify_user', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL) . ">confirme account</a>!</h3>
            <br />May the delivery force be with you!"
                    ]
                ]
            ];
            $response = $mj->post(Resources::$Email, ['body' => $body]);

            $response->success() && $response->getData();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UserRepository $usersRepository, EntityManagerInterface $em): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, "Azerty123;")){
            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $usersRepository->find($payload['user_id']);
            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user && !$user->isIsVerified()){
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('profile');
            }
        }
        // Ici un problème se pose dans le token
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }
}