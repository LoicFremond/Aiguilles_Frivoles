<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\Messages;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\MenuItemDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/Admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Les Aiguilles Frivoles')
            ->setFaviconPath('../uploads/images/favicon.png');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToRoute('Mon site', 'fa fa-home', 'home'),
            MenuItem::linkToDashboard('Dashboard', 'fas fa-tools'),
            MenuItem::section('Gestion du site'),
            MenuItem::linkToCrud('Articles', 'fas fa-gem', Product::class),
            MenuItem::linkToCrud('Collections', 'fas fa-copyright', Category::class),
            MenuItem::linkToCrud('Gérer les commandes', 'fas fa-shopping-cart', Order::class),
            MenuItem::linkToCrud('Gérer les évènements', 'fas fa-calendar-alt', Event::class),
            MenuItem::section('Utilisateurs'),
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-dizzy', User::class),
            MenuItem::linkToCrud('Messages', 'fas fa-book', Messages::class)
        ];
    }
}
