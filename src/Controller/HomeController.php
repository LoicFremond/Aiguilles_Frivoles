<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Messages;
use App\Form\MessageType;
use App\Repository\EventRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Service\CartManager;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SessionInterface $session, CartManager $cartManager, ProductRepository $productRepository, CategoryRepository $categoryRepository, EventRepository $eventRepository): Response
    {
        /** @var array $cart */
        $cart = $session->get("cart", []);

        $cartDatas = $cartManager->getDatasFromCart($cart);

        $session->set('cartTotal', $cartDatas['total']);
        $event = new Event;
        $event = $eventRepository->findBy(['status' => 1]);
        $categories = new Category;
        $categories = $categoryRepository->findAll();
        $products = new Product;
        $products = $productRepository->findProducts();
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'event' => $event,
            'dataCart' => $cartDatas['data'],
            'total' => $cartDatas['total'],
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     */
    public function contact(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, Request $request)
    {
        $date = new DateTimeImmutable();
        $message = new Messages();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreatedAt($date);
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Votre message a été envoyé');
            return $this->redirectToRoute('home');
        }

        $categories = new Category;
        $categories = $categoryRepository->findAll();

        return $this->render('home/contact.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
            'categories' => $categories,
        ]);

    }


    /**
     * @Route("/collection/{categorySlug}", name="category")
     * @ParamConverter("category", options={"mapping": {"categorySlug": "slug"}})
     * @param string             $categorySlug
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository  $productRepository
     * @return Response
     */
    public function showByCategory(
        string $categorySlug,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ): Response
    {
        $categories = new Category;
        $categories = $categoryRepository->findAll();
        if (!$categorySlug) {
            throw $this
                ->createNotFoundException('Pas de collection trouvée.');
        }

        $category = $categoryRepository->findOneBy(
            ['slug' => mb_strtolower($categorySlug)]
        );

        if (!$category) {
            throw $this->createNotFoundException(
                'La collection n\'existe pas ou a été supprimée.'
            );
        }

        $selectByCategory = $productRepository->findBy(
            ['category' => $category]
        );

        if (!$category) {
            throw $this->createNotFoundException(
                'La collection n\'existe pas ou a été supprimée.'
            );
        }

        return $this->render('home/category.html.twig', [
            'selectByCategory' => $selectByCategory,
            'category' => $category,
            'categories' => $categories,
        ]);
    }
    /**
     * @Route("/collections", name="categories")
     */
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories = new Category;
        $categories = $categoryRepository->findAll();
        return $this->render('home/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/collection/{categorySlug}/{productSlug}", name="article")
     * @ParamConverter("product", options={"mapping": {"productSlug": "slug"}})
     * @param Product $product
     */
    public function showArticle(SessionInterface $session, CartManager $cartManager, ProductRepository $productRepository, CategoryRepository $categoryRepository, Product $product): Response
    {
        /** @var array $cart */
        $cart = $session->get("cart", []);

        $cartDatas = $cartManager->getDatasFromCart($cart);

        $session->set('cartTotal', $cartDatas['total']);
        $categories = new Category;
        $categories = $categoryRepository->findAll();
        $product = $productRepository->findOneBy(
            ['id' => $product]
        );
        return $this->render('home/product.html.twig', [
            'dataCart' => $cartDatas['data'],
            'total' => $cartDatas['total'],
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(CategoryRepository $categoryRepository): Response
    {
        $categories = new Category;
        $categories = $categoryRepository->findAll();
        return $this->render('home/about.html.twig', [
            'categories' => $categories,
        ]);
    }

}
