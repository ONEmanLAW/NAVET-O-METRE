<?php

namespace App\Controller;

use App\Entity\User;
use App\Model\RegistrationDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_registration_register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload] RegistrationDTO $registrationDTO,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $user = new User();
        $user->setEmail($registrationDTO->email);
        $user->setPassword($passwordHasher->hashPassword($user, $registrationDTO->password));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, Response::HTTP_CREATED, [], ['groups' => ['user:read']]);
    }
}
