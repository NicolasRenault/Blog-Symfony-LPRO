<?php

namespace App\Article;

use App\Entity\Article;
use App\Entity\ArticleModel;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ArticleEvent
{
    /** @var ArticleMailer */

    private $articleMailer;
    /**
    * Creation constructor.
    * @param ArticleMailer $articleMailer
    */

    public function __construct(ArticleMailer $articleMailer)
    {
        $this->articleMailer = $articleMailer;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Article && ($entity->getNbViews()%10 == 0)) {
            $this->articleMailer->sendMail($entity);
            return;
        }
        // if(is_object($entity->getModel()) && $entity->getModel() instanceof ArticleModel) {
        //     return;
        // }

        // if($entity->getNbViews()%10 == 0)
        //     $this->articleMailer->send($entity);
    }
}