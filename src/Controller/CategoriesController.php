<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}