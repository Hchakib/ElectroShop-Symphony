<?php
// src/Service/Mail.php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mail
{
    private MailerInterface $mailer;
    private string $senderAddress;

    public function __construct(MailerInterface $mailer, string $senderAddress)
    {
        $this->mailer        = $mailer;
        $this->senderAddress = $senderAddress;
    }

    public function send(string $to, string $toName, string $subject, string $content): void
    {
        $email = (new Email())
            ->from($this->senderAddress)
            ->to(sprintf('%s <%s>', $toName, $to))
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }
}
