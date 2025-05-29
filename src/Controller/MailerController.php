<?php
namespace App\Controller;

use App\Service\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    public function index(Mail $mail): Response
   {
        // Symfony vous passe déjà un Mail construit avec MailerInterface et senderAddress
        $mail->send(
            'bonnal.tristan91@gmail.com',
            'Tristan',
            'test',
            'contenu'
        );
        return $this->redirectToRoute('home');
    }

}

