<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Messages;
use App\Entity\Order;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use App\Repository\MessagesRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Enregistrement", name="register", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(EntityManagerInterface $entityManager, Request $request, UserPasswordEncoderInterface $passwordEncoder, CategoryRepository $categoryRepository): Response
    {
        $categories = new Category;
        $categories = $categoryRepository->findAll();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form->get('Password')->getData()));
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Compte créé');
            return $this->redirectToRoute('home');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/Profil/{id}", name="profile")
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     * @param Request $request
     * @param User    $user
     * @return Response
     */
    public function edit(EntityManagerInterface $entityManager, Request $request, User $user, CategoryRepository $categoryRepository): Response
    {
        $categories = new Category;
        $categories = $categoryRepository->findAll();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('/user/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User    $user
     * @return Response
     */
    public function delete(EntityManagerInterface $entityManager, Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('Delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

        /**
     * @Route("/Profil/{id}/Messages", name="messages")
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     * @param Request $request
     * @param User    $user
     * @return Response
     */
    public function messages(User $user, CategoryRepository $categoryRepository, MessagesRepository $messagesRepository): Response
    {
        $id = $user->getId();
        $categories = new Category;
        $categories = $categoryRepository->findAll();
        $messages = new Messages;
        $messages = $messagesRepository->findBy(
            ['recipient' => $id],
            ['id' => 'DESC']
        );

        return $this->render('/user/messages.html.twig', [
            'user' => $user,
            'categories' => $categories,
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/Profil/{id}/commandes", name="orders")
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     */
    public function showOrder(CategoryRepository $categoryRepository, OrderRepository $orderRepository, User $user): Response
    {
        $categories = new Category;
        $categories = $categoryRepository->findAll();
        $id = $user->getId();
        $orders = new Order();
        $orders = $orderRepository->findBy(
            ['client' => $id]
        );

        return $this->render('home/order.html.twig', [
            'orders' => $orders,
            'categories' => $categories,
        ]);
    }
}
