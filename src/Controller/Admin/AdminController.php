<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Order;
use App\Repository\OrderRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    /**
     * @Route("/Admin/Commandes", name="orders")
     */
    public function showOrder(OrderRepository $orderRepository): Response
    {
        $orders = new Order();
        $orders = $orderRepository->findAll();

        return $this->render('admin/order.html.twig', [
            'orders' => $orders,
        ]);
    }
}
