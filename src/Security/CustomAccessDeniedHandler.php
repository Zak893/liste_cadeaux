<?php

// src/Security/CustomAccessDeniedHandler.php
namespace App\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class CustomAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        $content = 'Access Denied: You are not authorized to access this resource.';
        $response = new Response($content, Response::HTTP_FORBIDDEN);

        return $response;    }
}
