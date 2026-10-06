<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Model\MovieDTO;
use App\Model\MovieFilterDTO;
use App\Model\PaginationDTO;
use App\Model\Paginator;
use App\Model\QueryDTO;
use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
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
    public function list(
        MovieRepository $movieRepository,
        #[MapQueryString] MovieFilterDTO $filters = new MovieFilterDTO(),
        #[MapQueryString] PaginationDTO $pagination = new PaginationDTO(),
    ): JsonResponse
    {
        $query = $movieRepository->createFilteredQueryBuilder($filters);
        $paginator = (new Paginator())->paginate($query, $pagination);

        return $this->json($paginator, Response::HTTP_OK);
    }

    #[Route('/movies/{id}', name: 'app_movies_show', methods: ['GET'])]
    public function show(Movie $movie): JsonResponse
    {
        return $this->json($movie, Response::HTTP_OK);
    }

    #[Route('/movies', name: 'app_movies_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] MovieDTO $movieDTO,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository
    ): JsonResponse
    {
        $movie = new Movie();
        $this->fillMovie($movie, $movieDTO, $categoryRepository);

        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->json($movie, Response::HTTP_CREATED);
    }

    #[Route('/movies/{id}', name: 'app_movies_update', methods: ['PUT'])]
    public function update(
        Movie $movie,
        #[MapRequestPayload] MovieDTO $movieDTO,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository
    ): JsonResponse
    {
        $this->fillMovie($movie, $movieDTO, $categoryRepository);

        $entityManager->flush();

        return $this->json($movie, Response::HTTP_OK);
    }

    #[Route('/movies/{id}', name: 'app_movies_delete', methods: ['DELETE'])]
    public function delete(Movie $movie, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($movie);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    private function fillMovie(Movie $movie, MovieDTO $movieDTO, CategoryRepository $categoryRepository): void
    {
        $movie->setTitle($movieDTO->title);
        $movie->setDescription($movieDTO->description);
        $movie->setReleaseDate($movieDTO->releaseDate);

        $categories = $categoryRepository->findBy(['id' => $movieDTO->categoryIds]);
        if (count($categories) !== count(array_unique($movieDTO->categoryIds))) {
            throw new UnprocessableEntityHttpException('Une ou plusieurs catégories n\'existent pas.');
        }

        $movie->getCategories()->clear();
        foreach ($categories as $category) {
            $movie->addCategory($category);
        }
    }
}