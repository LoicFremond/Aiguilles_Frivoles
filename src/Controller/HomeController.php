<?php

namespace App\Controller;

use App\Service\GetCategory;
use App\Entity\Event;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Messages;
use App\Entity\Status;
use App\Form\MessageType;
use App\Repository\EventRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\StatusRepository;
use App\Service\CartManager;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @property array categories
 */
class HomeController extends AbstractController
{

    public function __construct(GetCategory $category)
    {
        $this->categories = $category->getCategory();
    }

    /**
     * @Route("/", name="home")
     */
    public function index(
        SessionInterface $session,
        CartManager $cartManager,
        ProductRepository $productRepository,
        EventRepository $eventRepository
    ): Response {
        /** @var array $cart */
        $cart = $session->get("cart", []);
        $cartDatas = $cartManager->getDatasFromCart($cart);
        $session->set('cartTotal', $cartDatas['total']);
        $event = new Event;
        $event = $eventRepository->findBy(['status' => 1]);
        $products = new Product;
        $products = $productRepository->findProducts();
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'categories' => $this->categories,
            'event' => $event,
            'dataCart' => $cartDatas['data'],
            'total' => $cartDatas['total'],
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     */
    public function contact(
        StatusRepository $statusRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ) {
        $date = new DateTimeImmutable();
        $message = new Messages();
        /** @var Status $status */
        $status = $statusRepository->findOneBy(['status' => 'En attente']);
        $message->setStatus($status);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreatedAt($date);
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Votre message a été envoyé');
            return $this->redirectToRoute('home');
        }
        return $this->render('home/contact.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
            'categories' => $this->categories,
        ]);

    }

    /**
     * @Route("/collection/{categorySlug}", name="category")
     * @ParamConverter("category", options={"mapping": {"categorySlug": "slug"}})
     * @param string             $categorySlug
     * @param ProductRepository  $productRepository
     * @return Response
     */
    public function showByCategory(
        string $categorySlug,
        CategoryRepository $categoryRepository,
        SessionInterface $session,
        CartManager $cartManager,
        ProductRepository $productRepository
    ): Response
    {
        /** @var array $cart */
        $cart = $session->get("cart", []);
        $cartDatas = $cartManager->getDatasFromCart($cart);
        $session->set('cartTotal', $cartDatas['total']);
        $categories = new Category;
        $categories = $categoryRepository->findAll();

        $category = $categoryRepository->findOneBy(
            ['slug' => mb_strtolower($categorySlug)]
        );

        $selectByCategory = $productRepository->findBy(
            ['category' => $category],
            ['status' => 'DESC']
        );

        return $this->render('home/category.html.twig', [
            'selectByCategory' => $selectByCategory,
            'category' => $category,
            'categories' => $categories,
            'dataCart' => $cartDatas['data'],
            'total' => $cartDatas['total'],
        ]);
    }

    /**
     * @Route("/collections", name="categories")
     */
    public function categories(): Response
    {
        return $this->render('home/categories.html.twig', [
            'categories' => $this->categories,
        ]);
    }

    /**
     * @Route("/collection/{categorySlug}/{productSlug}", name="article")
     * @ParamConverter("product", options={"mapping": {"productSlug": "slug"}})
     * @param Product $product
     */
    public function showArticle(
        SessionInterface $session,
        CartManager $cartManager,
        ProductRepository $productRepository,
        Product $product
    ): Response {
        /** @var array $cart */
        $cart = $session->get("cart", []);
        $cartDatas = $cartManager->getDatasFromCart($cart);
        $session->set('cartTotal', $cartDatas['total']);
        $product = $productRepository->findOneBy(
            ['id' => $product]
        );
        return $this->render('home/product.html.twig', [
            'dataCart' => $cartDatas['data'],
            'total' => $cartDatas['total'],
            'product' => $product,
            'categories' => $this->categories,
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('home/about.html.twig', [
            'categories' => $this->categories,
        ]);
    }

}
