<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }

}
