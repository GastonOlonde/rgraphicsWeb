<?php

namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class mailcontact{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(
        string $from, 
        string $to,
        string $subject,
        string $message
    ):void 
    {
        // On créer le mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to('info@rgraphics.fr')
            ->subject($subject)
            ->context($message);

        // On envoie le mail
        $this->mailer->send($email);

    }
}