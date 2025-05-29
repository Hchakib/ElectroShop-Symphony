<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Model\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * Création d'une commande "one-click" :
     * on prend le panier, on génère l'Order et ses OrderDetails, puis on redirige vers Stripe.
     *
     * @param Cart                     $cart  Service cart qui contient ['products', 'totals']
     * @param EntityManagerInterface   $em
     */
    #[Route('/commande', name: 'order')]
    public function index(Cart $cart, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cartData = $cart->getDetails();
        if (empty($cartData['products'])) {
            return $this->redirectToRoute('cart');
        }

        // 1) Création de l'entité Order
        $order = new Order();
        $order
            ->setUser($user)
            ->setState(0)
            ->setTotal($cartData['totals']['price']); // total en cents

        $em->persist($order);

        // 2) Création des OrderDetails
        foreach ($cartData['products'] as $item) {
            $detail = new OrderDetails();
            $detail
                ->setBindedOrder($order)
                ->setProduct($item['product']->getName())
                ->setQuantity($item['quantity'])
                ->setPrice($item['product']->getPrice())
                ->setTotal($item['product']->getPrice() * $item['quantity']);
            $em->persist($detail);
        }

        $em->flush();

        // 3) Redirection vers la page de paiement (Stripe Checkout)
        return $this->redirectToRoute('checkout', [
            'reference' => $order->getReference(),
        ]);
    }
}
