<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Entity\Order;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\MessagesRepository;
use App\Repository\OrderRepository;
use App\Service\GetCategory;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @property array categories
 */
class UserController extends AbstractController
{

    public function __construct(GetCategory $category)
    {
        $this->categories = $category->getCategory();
    }

    /**
     * @Route("/inscription", name="register", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(
        EntityManagerInterface $entityManager,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form->get('Password')->getData()));
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Compte créé');
            return $this->redirectToRoute('home');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'categories' => $this->categories,
        ]);
    }

    /**
     * @Route("/profil/{id}", name="profile")
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     * @param Request $request
     * @param User    $user
     * @return Response
     */
    public function edit(
        EntityManagerInterface $entityManager,
        Request $request,
        User $user
    ): Response {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('/user/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'categories' => $this->categories,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User    $user
     * @return Response
     */
    public function delete(
        EntityManagerInterface $entityManager,
        Request $request,
        User $user
    ): Response {

        if ($this->isCsrfTokenValid('Delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

        /**
     * @Route("/profil/{id}/messages", name="messages")
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     * @param Request $request
     * @param User    $user
     * @return Response
     */
    public function messages(
        User $user,
        MessagesRepository $messagesRepository
    ): Response {

        $id = $user->getId();
        $messages = new Messages;
        $messages = $messagesRepository->findBy(
            ['recipient' => $id],
            ['id' => 'DESC']
        );

        return $this->render('/user/messages.html.twig', [
            'user' => $user,
            'categories' => $this->categories,
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/profil/{id}/commandes", name="orders")
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     */
    public function showOrder(
        OrderRepository $orderRepository,
        User $user
        ): Response {

        $id = $user->getId();
        $orders = new Order();
        $orders = $orderRepository->findBy(
            ['client' => $id]
        );

        return $this->render('home/order.html.twig', [
            'orders' => $orders,
            'categories' => $this->categories,
        ]);
    }
}
