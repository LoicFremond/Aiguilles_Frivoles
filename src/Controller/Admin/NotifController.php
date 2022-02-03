<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Messages;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\MessagesRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotifController extends AbstractDashboardController
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
    /**
     * @Route("/admin/notif", name="notif")
     */
    public function notif(OrderRepository $orderRepository, MessagesRepository $messagesRepository, UserRepository $userRepository): Response
    {
        $users = new User();
        $users = $userRepository->findAll();
        $orders = new Order();
        $orders = $orderRepository->findBy(['status' => 1]);

        $messages = new Messages();
        $messages = $messagesRepository->findBy(['recipient' => 1, 'status' => 1]);
        return $this->render('admin/notif.html.twig', [
            'orders' => $orders,
            'messages' => $messages,
            'users' => $users
        ]);
    }
}
