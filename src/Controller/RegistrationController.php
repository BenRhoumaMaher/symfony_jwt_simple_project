<?php

namespace App\Controller;

use App\Dto\RegistrationDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
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
        LoggerInterface $logger,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {

        $logger->info(
            'New Registration attempt',
            [
            'email' => $requestDto->email
            ]
        );

        try {
            $user = new User();
            $user->setEmail($requestDto->email);

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $requestDto->password
            );
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $logger->info(
                'User registered successfully',
                [
                    'id' => $user->getId()
                ]
            );

            return new JsonResponse(
                ['message' => 'User registered successfully'],
                201
            );
        } catch (\Exception $e) {
            $logger->error(
                'Registration failed',
                [
                    'message' => $e->getMessage(),
                    'email' => $requestDto->email
                ]
            );

            return new JsonResponse(
                ['error' => 'Registration failed'],
                500
            );
        }
    }
}
