<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Rating;
use App\Entity\User;
use App\Model\PaginationDTO;
use App\Model\Paginator;
use App\Model\RatingDTO;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
#[IsGranted('ROLE_USER')]
final class RatingsController extends AbstractController
{
    #[Route('/movies/{id}/rating', name: 'app_ratings_rate', methods: ['PUT'])]
    public function rate(
        Movie $movie,
        #[MapRequestPayload] RatingDTO $ratingDTO,
        #[CurrentUser] User $user,
        RatingRepository $ratingRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $rating = $ratingRepository->findOneBy(['user' => $user, 'movie' => $movie]);
        $status = Response::HTTP_OK;

        if ($rating === null) {
            $rating = (new Rating())->setUser($user)->setMovie($movie);
            $entityManager->persist($rating);
            $status = Response::HTTP_CREATED;
        }

        $rating->setScore($ratingDTO->score);
        $rating->setRatedAt(new \DateTimeImmutable());

        $entityManager->flush();

        return $this->json($rating, $status);
    }

    #[Route('/movies/{id}/rating', name: 'app_ratings_show', methods: ['GET'])]
    public function show(Movie $movie, #[CurrentUser] User $user, RatingRepository $ratingRepository): JsonResponse
    {
        $rating = $ratingRepository->findOneBy(['user' => $user, 'movie' => $movie])
            ?? throw $this->createNotFoundException('Vous n\'avez pas encore noté ce film.');

        return $this->json($rating, Response::HTTP_OK);
    }

    #[Route('/movies/{id}/rating', name: 'app_ratings_delete', methods: ['DELETE'])]
    public function delete(
        Movie $movie,
        #[CurrentUser] User $user,
        RatingRepository $ratingRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $rating = $ratingRepository->findOneBy(['user' => $user, 'movie' => $movie])
            ?? throw $this->createNotFoundException('Vous n\'avez pas encore noté ce film.');

        $entityManager->remove($rating);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/me/ratings', name: 'app_ratings_feed', methods: ['GET'])]
    public function feed(
        #[CurrentUser] User $user,
        RatingRepository $ratingRepository,
        #[MapQueryString] PaginationDTO $pagination = new PaginationDTO(),
    ): JsonResponse
    {
        $query = $ratingRepository->createFeedQueryBuilder($user);
        $paginator = (new Paginator())->paginate($query, $pagination);

        return $this->json($paginator, Response::HTTP_OK);
    }
}
