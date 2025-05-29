<?php

namespace App\Controller;

use App\Entity\Order;
use App\Model\Cart;
use App\Repository\OrderRepository;
use App\Service\Mail;
use App\Service\PaypalClient;
use Doctrine\ORM\EntityManagerInterface;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    private EntityManagerInterface $em;
    private PayPalHttpClient $paypalClient;
    private Mail $mailer;

    public function __construct(EntityManagerInterface $em, Mail $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->paypalClient = PaypalClient::client();
    }

    /**
     * Création de la commande PayPal et redirection vers l’approbation
     */
    #[Route('/commande/checkout/{reference}', name: 'checkout', methods: ['GET'])]
    public function checkout(OrderRepository $orderRepo, string $reference): Response
    {
        $order = $orderRepo->findOneByReference($reference);
        if (!$order) {
            throw $this->createNotFoundException('Commande introuvable.');
        }

        // Calcul du montant total (CAD)
        $totalCents = 0;
        foreach ($order->getOrderDetails() as $item) {
            $totalCents += $item->getPrice() * $item->getQuantity();
        }
        $amount = number_format($totalCents / 100, 2, '.', '');

        // Création de la requête PayPal
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $order->getReference(),
                'amount'       => ['currency_code' => 'CAD', 'value' => $amount]
            ]],
            'application_context' => [
                'cancel_url' => $this->generateUrl('payment_fail', ['reference' => $reference], UrlGeneratorInterface::ABSOLUTE_URL),
                'return_url' => $this->generateUrl('payment_success', ['reference' => $reference], UrlGeneratorInterface::ABSOLUTE_URL),
            ]
        ];

        // Exécution et stockage de l’ID PayPal
        $response = $this->paypalClient->execute($request);
        $order->setPaypalOrderId($response->result->id);
        $this->em->flush();

        // Redirection vers l’URL d’approbation
        foreach ($response->result->links as $link) {
            if ($link->rel === 'approve') {
                return $this->redirect($link->href);
            }
        }

        throw $this->createNotFoundException('URL d’approbation PayPal introuvable.');
    }

    /**
     * Callback PayPal en cas de succès
     */
    #[Route('/commande/valide/paypal/{reference}', name: 'payment_success', methods: ['GET'])]
    public function paymentSuccess(
        OrderRepository $orderRepo,
        string $reference,
        Cart $cart
    ): Response {
        $order = $orderRepo->findOneByReference($reference);
        if (!$order || $order->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('Commande inaccessible');
        }

        // Capture du paiement
        $captureReq = new OrdersCaptureRequest($order->getPaypalOrderId());
        $captureReq->prefer('return=representation');
        $capture = $this->paypalClient->execute($captureReq);

        if ($capture->result->status === 'COMPLETED') {
            if (!$order->getState()) {
                $order->setState(1);
                $this->em->flush();
            }

            // Envoi du mail de confirmation
            $user = $this->getUser();
            $this->mailer->send(
                $user->getEmail(),
                $user->getFirstname(),
                "Confirmation commande {$order->getReference()}",
                "Bonjour {$user->getFirstname()}, merci pour votre commande !"
            );

            // Vider le panier
            $cart->remove();

            return $this->render('payment/success.html.twig', ['order' => $order]);
        }

        return $this->redirectToRoute('payment_fail', ['reference' => $reference]);
    }

    /**
     * Callback PayPal en cas d’annulation
     */
    #[Route('/commande/echec/paypal/{reference}', name: 'payment_fail', methods: ['GET'])]
    public function paymentFail(
        OrderRepository $orderRepo,
        string $reference
    ): Response {
        $order = $orderRepo->findOneByReference($reference);
        if (!$order || $order->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('Commande inaccessible');
        }

        return $this->render('payment/fail.html.twig', ['order' => $order]);
    }
}
