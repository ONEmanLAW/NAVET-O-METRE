<?php

namespace App\Controller;

use App\Entity\Category;
use App\Model\CategoryDTO;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'app_categories_list', methods: ['GET'])]
    public function list(CategoryRepository $categoryRepository): JsonResponse {
        $categories = $categoryRepository->findAll();

        return $this->json($categories, Response::HTTP_OK);
    }

    #[Route('/categories/{id}', name: 'app_categories_show', methods: ['GET'])]
    public function show(Category $category): JsonResponse {
        return $this->json($category, Response::HTTP_OK);
    }

    #[Route('/categories', name: 'app_categories_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CategoryDTO $categoryDTO,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $category = new Category();
        $category->setTitle($categoryDTO->title);

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category, Response::HTTP_CREATED);
    }

    #[Route('/categories/{id}', name: 'app_categories_update', methods: ['PUT'])]
    public function update(
        Category $category,
        #[MapRequestPayload] CategoryDTO $categoryDTO,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $category->setTitle($categoryDTO->title);

        $entityManager->flush();

        return $this->json($category, Response::HTTP_OK);
    }

    #[Route('/categories/{id}', name: 'app_categories_delete', methods: ['DELETE'])]
    public function delete(Category $category, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
