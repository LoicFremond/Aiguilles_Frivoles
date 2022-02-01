<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\CartManager;
use App\Service\OrderManager;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route("/cart", name="cart_")
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
        CartManager $cartManager,
        ProductRepository $productRepository
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


        return $this->redirectToRoute("cart_index");
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
     * @Route("/delete-all/", name="delete_all")
     * @param SessionInterface $session
     * @return Response A response instance
     */
    public function deleteAll(SessionInterface $session): Response
    {
        $session->set("cart", []);

        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/basket", name="basket")
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
        $session->set("cart", []);

        $this->addFlash('success', 'Votre commande a été passée avec succès');

        return $this->redirectToRoute('home');
    }
}
