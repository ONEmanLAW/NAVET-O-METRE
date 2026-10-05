<?php

namespace App\Controller;

use App\Model\QueryDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class MoviesController extends AbstractController
{
    #[Route('/movies', name: 'app_movies')]
    public function index(
        Request $request,
        #[MapQueryString] QueryDTO $queryDTO
    ): JsonResponse
    {
        return $this->json($queryDTO);
    }
}