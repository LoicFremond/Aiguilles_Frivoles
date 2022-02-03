<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Order;
use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractDashboardController
{
    /**
     * @Route("/admin/commandes", name="order")
     */
    public function showOrder(CategoryRepository $categoryRepository, OrderRepository $orderRepository): Response
    {
        $categories = new Category;
        $categories = $categoryRepository->findAll();
        $orders = new Order();
        $orders = $orderRepository->findAll();

        return $this->render('admin/order.html.twig', [
            'orders' => $orders,
            'categories' => $categories,
        ]);
    }
}
