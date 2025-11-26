<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api/profile', name: 'api_profile', methods: ['GET'])]
    public function profile(): Response
    {
        return $this->json(
            [
            'message' => 'This is a protected profile route',
            'user' => $this->getUser()->getEmail(),
            ]
        );
    }
}
