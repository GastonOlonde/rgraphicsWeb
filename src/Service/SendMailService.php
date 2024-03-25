<?php

namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class SendMailService{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(
        string $from, 
        string $to,
        string $subject,
        string $template,
        array $context
    ):void 
    {
        // On créer le mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to('info@rgraphics.fr')
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        // On envoie le mail
        $this->mailer->send($email);

    }
}