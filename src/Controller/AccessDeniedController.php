<?php
// src/Controller/AccessDeniedController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccessDeniedController
{
    #[Route('/access-denied', name: 'app_access_denied')]
    public function index(): Response
    {
        // Customize the access denied response
        return new Response('Access Denied: You are not authorized to access this resource.', Response::HTTP_FORBIDDEN);
    }
}
