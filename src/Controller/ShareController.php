<?php

namespace App\Controller;

use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ShareController extends AbstractController
{
    #[Route('/share', name: 'app_share')]
    public function share(Request $request): Response
    {
        // Récupérer la valeur de l'input depuis la requête
        $email = $request->get('email'); // Assurez-vous que 'input_value' correspond à l'attribut 'name' de votre input
        $url = $request->get('url'); // Assurez-vous que 'input_value' correspond à l'attribut 'name' de votre input
        $apikey = '34c0e4be87b475c66927bb120498351e';
        $apisecret = '2dea9bfc2df41294816bcd5bb506c3cf';
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];
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
                            'Email' => $email,
                            'Name' => "You"
                        ]
                    ],
                    'Subject' => "Cadeaux!",
                    'TextPart' => "SYMFONY!",
                    'HTMLPart' => "hey dear une personne qui t'aime bien veut partager une liste des cadeaux avec toi ;) <a href='" . $url . "'>trouve ta liste ici</a>"
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);

        $response->success() && $response->getData();
        return $this->redirectToRoute('list_type');
        // Retournez une réponse (par exemple, une réponse JSON)
    }
}
