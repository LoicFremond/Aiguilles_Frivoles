<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use App\Service\CartManager;
use App\Service\OrderManager;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/panier", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET", "POST"})
     * @param SessionInterface $session
     * @param CartManager $cartManager
     * @return Response A response instance
     */
    public function index(
        SessionInterface $session,
        ProductRepository $productRepository,
        CartManager $cartManager,
        CategoryRepository $categoryRepository
    ): Response {
        /** @var array $cart */
        $cart = $session->get("cart", []);
        $cartDatas = $cartManager->getDatasFromCart($cart);
        $session->set('cartTotal', $cartDatas['total']);
        $categories = new Category;
        $categories = $categoryRepository->findAll();

        return $this->render('cart/index.html.twig', [
            'dataCart' => $cartDatas['data'],
            'products' => $productRepository->findAll(),
            'total' => $cartDatas['total'],
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/add/{id}", name="add")
     * @ParamConverter("Product", options={"mapping": {"id": "id"}})
     * @param SessionInterface $session
     * @param Product $product
     * @param CartManager $cartManager
     * @return Response A response instance
     */
    public function add(
        Product $product,
        SessionInterface $session,
        CartManager $cartManager
    ): Response {
        /** @var array $cart */
        $cart = $session->get("cart", []);
        /** @var int $id */
        $id = $product->getId();
        if (is_array($cart)) {
            if (array_key_exists($id, $cart)) {
                $cart[$id]++;
            } else {
                $cart[$id] = 1;
            }
        }

        $cartDatas = $cartManager->getDatasFromCart($cart);
        $session->set('cartTotal', $cartDatas['total']);
        $session->set("cart", $cart);

        return $this->redirect($_SERVER['HTTP_REFERER']);
        }

    /**
     * @Route("/delete/{id}", name="delete")
     * @ParamConverter("product", options={"mapping": {"id": "id"}})
     * @param Product $product
     * @return Response A response instance
     */
    public function delete(Product $product, SessionInterface $session): Response
    {
        $cart = $session->get("cart", []);
        /** @var int $id */
        $id = $product->getId();
        if (is_array($cart)) {
            if (array_key_exists($id, $cart)) {
                unset($cart[$id]);
            }
            $session->set("cart", $cart);
        }

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/empty/", name="delete_all")
     * @param SessionInterface $session
     * @return Response A response instance
     */
    public function deleteAll(SessionInterface $session): Response
    {
        $session->set("cart", []);

        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/validate", name="basket")
     * @param User $user
     * @throws Exception
     */
    public function validateBasket(
        SessionInterface $session,
        CartManager $cartManager,
        OrderManager $orderManager,
        EntityManagerInterface $ema
    ): RedirectResponse {

        $date = new DateTimeImmutable();
        /** @var array $cart */
        $cart = $session->get("cart", []);
        $data = $cartManager->getDatasFromCart($cart);
        $newCart = $orderManager->orderCartData($data);
        $newOrder = new Order();
        /** @var User $user */
        $user = $this->getUser();
        foreach ($newCart as $products) {
            foreach ($products as $product) {
                $newOrder->addProduct($product);
                $newOrder->setPrice($product->getPrice());
                $newOrder->getId($newOrder);
                $newOrder->setClient($user);
                $ema->persist($newOrder);
            }
        }
        $newOrder->setCreatedAt($date);
        $newOrder->setPrice($data['total']);
        $ema->flush();

        $this->addFlash('success', 'Votre commande a été passée avec succès. Je vous contacterai sous peu pour procéder à la finalisation et au réglement ou vous pouvez payer dès maintenant.');

        return $this->redirectToRoute('cart_payment');
    }

    /**
     * @Route("/payment", name="payment", methods={"GET", "POST"})
     * @param SessionInterface $session
     * @param CartManager $cartManager
     * @return Response A response instance
     */
    public function payment(
        SessionInterface $session,
        ProductRepository $productRepository,
        CartManager $cartManager,
        CategoryRepository $categoryRepository
    ): Response {
        /** @var array $cart */
        $cart = $session->get("cart", []);

        $cartDatas = $cartManager->getDatasFromCart($cart);

        $session->set('cartTotal', $cartDatas['total']);

        $categories = new Category;
        $categories = $categoryRepository->findAll();
        return $this->render('cart/payment.html.twig', [
            'dataCart' => $cartDatas['data'],
            'products' => $productRepository->findAll(),
            'total' => $cartDatas['total'],
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/payment/finish", name="finish")
     */
    public function finish(
        SessionInterface $session
    ): Response {
        $session->set("cart", []);

        return $this->redirectToRoute('home');
    }
}
