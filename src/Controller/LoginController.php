<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/Connexion", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @param CategoryRepository  $categoryRepository
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, CategoryRepository $categoryRepository): Response
    {

        $categories = new Category;
        $categories = $categoryRepository->findAll();
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'login/index.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                'categories' => $categories,
            ]
        );
    }

    /**
     * @Route("/Deconnection", name="logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
