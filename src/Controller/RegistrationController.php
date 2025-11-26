<?php

namespace App\Controller;

use App\Dto\RegistrationDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'user_registration', methods: ['POST'])]
    public function register(
        #[MapRequestPayload] RegistrationDto $requestDto,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {

        $user = new User();
        $user->setEmail($requestDto->email);

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $requestDto->password
        );
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(
            ['message' => 'User registered successfully'],
            201
        );
    }
}
