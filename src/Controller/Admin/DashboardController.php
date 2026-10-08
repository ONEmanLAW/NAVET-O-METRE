<?php

namespace App\Controller\Admin;

use App\Repository\MovieRepository;
use App\Repository\RatingRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private MovieRepository $movieRepository,
        private UserRepository $userRepository,
        private RatingRepository $ratingRepository,
    ) {}

    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'moviesCount' => $this->movieRepository->count(),
            'usersCount' => $this->userRepository->count(),
            'ratingsCount' => $this->ratingRepository->count(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TheFilmApp Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'app_front');
    }
}
