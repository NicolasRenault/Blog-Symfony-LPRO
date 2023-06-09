<?php

namespace App\Article;

use App\Entity\Article;

class ArticleMailer
{
    /** @var \Swift_Mailer */
    private $mailer;

    /**
    * Mailer constructor.
    * @param $mailer
    */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(Article $article)
    {
        $mailAdresse = 'nicolas.renault@etu.univ-poitiers.fr';
        $message = new \Swift_Message(
        "L'Article {$article->getTitle()} est tendance !",
        "Information : votre article viens d'atteindre les {$article->getNbViews()} vues !"
        );
        $message
        ->addTo($mailAdresse)
        ->addFrom($mailAdresse);
        $this->mailer->send($message);
    }
}