<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{

    public function sendEmail(MailerInterface $mailer
    ): void

    {
        $html='See Twig integration for better HTML integration!';
        $email = (new Email())
            ->from('articamp4se5@gmail.com')
            ->to('islem.yacoubi@esprit.tn')
            ->subject('Time for Symfony Mailer!')
            ->html($html);

            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            //->text('Sending emails is fun again!')
            //->html($content);

        $mailer->send($email);

        // ...

    }


}