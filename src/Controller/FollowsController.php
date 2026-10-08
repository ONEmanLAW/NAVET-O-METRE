<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
#[IsGranted('ROLE_USER')]
final class FollowsController extends AbstractController
{
    #[Route('/users/{id}/follow', name: 'app_follows_follow', methods: ['PUT'])]
    public function follow(
        User $followed,
        #[CurrentUser] User $user,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        if ($followed->getId() === $user->getId()) {
            throw new UnprocessableEntityHttpException('Vous ne pouvez pas vous suivre vous-même.');
        }

        $user->follow($followed);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/users/{id}/follow', name: 'app_follows_unfollow', methods: ['DELETE'])]
    public function unfollow(
        User $followed,
        #[CurrentUser] User $user,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $user->unfollow($followed);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/me/following', name: 'app_follows_following', methods: ['GET'])]
    public function following(#[CurrentUser] User $user): JsonResponse
    {
        return $this->json($user->getFollowing()->getValues(), Response::HTTP_OK, [], ['groups' => ['user:read']]);
    }

    #[Route('/me/followers', name: 'app_follows_followers', methods: ['GET'])]
    public function followers(#[CurrentUser] User $user): JsonResponse
    {
        return $this->json($user->getFollowers()->getValues(), Response::HTTP_OK, [], ['groups' => ['user:read']]);
    }
}
