<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $users = $manager->getRepository('App:User')->findAll();
        // if ($users != []) {
        //     $user1 = $users[0];
        //     $user2 = $users[1];
        // } else {
        // $user1 = new User();
        // $user1->setUsername('root')->setPassword('root')->setRole('ROLE_ADMIN')->setSalt("");
        //     $user2 = new User();
        //     $user2->setUsername('user')->setPassword('user')->setRole('ROLE_USER')->setSalt("");
        //     $manager->persist($user1);
        //     $manager->persist($user2);
        // }

        for ($i=0; $i < 3; $i++) { 
            $article1 = new Article();
            $article1->setTitle("Despacito ?")->setAuthor("Nico le fou")->setContent("
            Au collège, j'étais cheum mais déter' comme un chleuh
            J'volais des Playstation, j'les revendais et j'm'achetais des jeux
            Y'a quoi dans la tête des jeunes? J'traînais dans tout Beriz
            J'faisais des piques avec du gel, j'avais la coupe de Reese
            ")->setCreatedAt(new DateTime())->setUpdatedAt(new DateTime())->setNbViews(0)->setPublished(true);
            $comment1 = new Comment();
            $comment1->setTitle("Com 1")->setAuthor("Com 1")->setMessage('Fabuleux')->setCreatedAt(new DateTime());
            
            sleep(1);
            
            $article2 = new Article();
            $article2->setTitle("Ma ****")->setAuthor("Alex le silex")->setContent("
            It's easy to be right when everything repeats
            It's easier to cut us down and point at our defeat
            It's easy to be right when everything repeats
            Dig below the surface find your insecurities
            ")->setCreatedAt(new DateTime())->setUpdatedAt(new DateTime())->setNbViews(0)->setPublished(true);
            $comment2 = new Comment();
            $comment2->setTitle("Com 2")->setAuthor("Com 2")->setMessage('Flemme')->setCreatedAt(new DateTime());
            sleep(1);

            $article3 = new Article();
            $article3->setTitle("Meme MM m'aime")->setAuthor("Aurel l'abeille")->setContent("
            Puis y'a les jumelles, que j'ai ammené à l'hôtel
            Une d'entre elle, était fan du PSG
            Pendant qu'elle me *bip*, je criais allez l'OM
            Je te raconte pas la suite, poto j'ai trop la flemme
            Je vais toutes vous snitch, comme 6ix9ine au tribunal
            Et y'a Caroline, son frère dormait à l'étage
            Puis il nous a surpris et je suis parti en cavale
            Il m'a rattrapé, il m'a bien niqué ma race
            ")->setCreatedAt(new DateTime())->setUpdatedAt(new DateTime())->setNbViews(0)->setPublished(true);
            $comment3 = new Comment();
            $comment3->setTitle("Com 3")->setAuthor("Com3")->setMessage('Oui oui oui oui oui')->setCreatedAt(new DateTime());
            sleep(1);
            
            $article4 = new Article();
            $article4->setTitle("Il a pas de gow")->setAuthor("Flo le pédalo")->setContent("
            Flemme.
            ")->setCreatedAt(new DateTime())->setUpdatedAt(new DateTime())->setNbViews(0)->setPublished(false);
            $comment4 = new Comment();
            $comment4->setTitle("Com 4")->setAuthor("Com 4")->setMessage('Aurel est très gentil')->setCreatedAt(new DateTime());
            sleep(1);

            $article5 = new Article();
            $article5->setTitle("B2CB")->setAuthor("Sheitan")->setContent("
            La recette interdite, perdue depuis des miliers d'années. La légende raconte que le
            B2CB, autrement dis Burger double cordons bleu serait a l'origine de la première
            grande guerre de religion. Un démon très puissant est lié directement avec cette
            recette aujourd'hui prohibée.
            
            Le seul conseil que l'on peut vous donner : Le vomis tactique.
            ")->setCreatedAt(new DateTime())->setUpdatedAt(new DateTime())->setNbViews(0)->setPublished(true);
            $comment5 = new Comment();
            $comment5->setTitle("Com 5")->setAuthor("Com 5")->setMessage('Giga propre')->setCreatedAt(new DateTime());
            sleep(1);
            $comment6 = new Comment();
            $comment6->setTitle("Com 6")->setAuthor("Com 6")->setMessage('Eclaté')->setCreatedAt(new DateTime());

            $articleClara = new Article();
            $articleClara->setTitle('La blg')->setAuthor('Clara babybou')->setContent("
            J'ai l'impression que dans ma tête, il y fait nuit depuis des jours
            Assise à la fenêtre, le ciel pleure, il pleut encore sur mes joues
            Quand t'es plus là, le temps s'arrête
            Pourtant, les aiguilles tournent autour
            Il reviendra demain peut-être enfin
            Si demain veut bien faire demi-tour
            Demi-tour
            Il pleut dans mes rêves
            J'suis fatiguée mais je n'ai pas sommeil
            Est-ce qu'on bronze ou on brûle au soleil?
            Tout part en fumée, Absolem
            Autant de sang dans de si petites veines
            T'as bien fait de m'arracher les ailes
            J'entends d'ici Amy et Kurt Cobain
            Tout part en fumée, Absolem
            Absolem, Absolem, Absolem, Absolem, Absolem, Absolem, Absolem
            ")->setCreatedAt(new DateTime())->setUpdatedAt(new DateTime())->setNbViews(0)->setPublished(true);

            $comment7 = new Comment();
            $comment7->setTitle('First')->setAuthor('Pikabuuu')->setMessage('Je suis le premier wola')->setCreatedAt(new DateTime());

            $article1->addComment($comment1)->addComment($comment2);
            $article2->addComment($comment3);
            $article4->addComment($comment4);
            $article5->addComment($comment5)->addComment($comment6);
            $articleClara->addComment($comment7);

            $categories = $manager->getRepository('App:Category')->findAll();
            if ($categories != []) {
                $category1 = $categories[0];
                $category2 = $categories[1];
                $category3 = $categories[2];
            } else {
                $category1 = new Category();
                $category1->setName("Nouriture");
                $category2 = new Category();
                $category2->setName("Musique");
                $category3 = new Category();
                $category3->setName("Amour");

                $manager->persist($category1);
                $manager->persist($category2);
                $manager->persist($category3);
            }

            $article1->addCategory($category2);
            $article2->addCategory($category1)->addCategory($category2)->addCategory($category3);
            $article3->addCategory($category3);
            $article4->addCategory($category3);
            $article5->addCategory($category1);
            $articleClara->addCategory($category2);

            $manager->persist($article1);
            $manager->persist($article2);
            $manager->persist($article3);
            $manager->persist($article4);
            $manager->persist($article5);
            $manager->persist($articleClara);

            $manager->persist($comment1);
            $manager->persist($comment2);
            $manager->persist($comment3);
            $manager->persist($comment4);
            $manager->persist($comment5);
            $manager->persist($comment6);
            $manager->persist($comment7);

            $manager->flush();
        }
    }
}
