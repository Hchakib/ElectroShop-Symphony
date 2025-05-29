<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\ProfileType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Espace membre (sécurisé)
 */
class AccountController extends AbstractController
{
    #[Route('/compte', name: 'account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
        ]);
    }

    /**
     * Permet la modification du mot de passe d'un utilisateur sur une page dédiée
     */
    #[Route('/compte/mot-de-passe', name: 'account_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_password = $form->get('old_password')->getData();
            $new_password = $form->get('new_password')->getData();
            $isOldPasswordValid = $passwordHasher->isPasswordValid($user, $old_password);
            if ($isOldPasswordValid) {
                $password = $passwordHasher->hashPassword($user,$new_password);
                $user->setPassword($password);
                $em->flush();
                $this->addFlash(
                    'notice', 
                    'Mot de passe modifié :)'
                );
                return $this->redirectToRoute('account');
            } else {
                $this->addFlash(
                    'notice', 
                    'Mot de passe actuel erroné :('
                );
            }
        }

        return $this->renderForm('account/password.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * Affiche la vue de toutes les commandes d'un utilisateur
     */
    #[Route('/compte/commandes', name: 'account_orders')]
    public function showOrders(OrderRepository $repository): Response
    {
        $orders = $repository->findPaidOrdersByUser($this->getUser());
        return $this->render('account/orders.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * Affiche une commande
     */
    #[Route('/compte/commandes/{reference}', name: 'account_order')]
    public function showOrder(Order $order): Response
    {
        if (!$order || $order->getUser() != $this->getUser()) {
            throw $this->createNotFoundException('Commande innaccessible');
        }
        return $this->render('account/order.html.twig', [
            'order' => $order
        ]);
    }




    #[Route('/mon-profil', name: 'account_profile')]
    public function editProfile(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1) Si l'utilisateur a saisi un ancien mot de passe, on tente de changer
            $oldPwd = $form->get('old_password')->getData();
            $newPwd = $form->get('new_password')->getData();

            if ($oldPwd || $newPwd) {
                // Les deux doivent être remplis pour changer
                if (!$oldPwd || !$newPwd) {
                    $this->addFlash('error', 'Pour changer le mot de passe, complétez les deux champs.');
                } elseif (!$passwordHasher->isPasswordValid($user, $oldPwd)) {
                    $this->addFlash('error', 'L\'ancien mot de passe est incorrect.');
                } else {
                    // Hash et enregistrement du nouveau mot de passe
                    $hashed = $passwordHasher->hashPassword($user, $newPwd);
                    $user->setPassword($hashed);
                    $this->addFlash('success', 'Votre mot de passe a été mis à jour.');
                }
            }

            // 2) Mise à jour des autres champs
            $em->flush();  // <-- utilisez $em, pas $this->em

            $this->addFlash('success', 'Profil mis à jour avec succès !');
            return $this->redirectToRoute('account_profile');
        }

        return $this->renderForm('account/profile.html.twig', [
            'form' => $form,
        ]);
    }
}
