<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Model\QueryDTO;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class MoviesController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function message(
        Request $request,
        #[MapQueryString] QueryDTO $queryDTO
    ): JsonResponse
    {
        return $this->json($queryDTO);
    }

    #[Route('/movies', name: 'app_movies_list', methods: ['GET'])]
    public function list(MovieRepository $movieRepository): JsonResponse
    {
        $movies = $movieRepository->findAll();

        return $this->json($movies, Response::HTTP_OK);
    }

    #[Route('/movies/{id}', name: 'app_movies_show', methods: ['GET'])]
    public function show(Movie $movie): JsonResponse
    {
        return $this->json($movie, Response::HTTP_OK);
    }

    #[Route('/movies/{id}', name: 'app_movies_delete', methods: ['DELETE'])]
    public function delete(Movie $movie, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($movie);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/newFilmTest', name: 'app_movies_example', methods: ['POST'])]
    public function example(EntityManagerInterface $entityManager): JsonResponse
    {
        $movie = new Movie();
        $movie->setTitle('Kebab simulator');
        $movie->setDescription('The kebab simulator');
        $movie->setReleaseDate(2027);

        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->json($movie, Response::HTTP_CREATED);
    }
}