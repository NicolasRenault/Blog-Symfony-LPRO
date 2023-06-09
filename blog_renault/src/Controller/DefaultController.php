<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/");
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/",
     * name="index")
     */
    public function index()
    {
        return $this->redirectToRoute('home');
    }
    /**
     * @Route("/{_locale}",
     * name="home")
     * @return mixed
     */
    public function homeAction()
    {
        $this->addFlash('info', 'FlashBag !');
        $this->addFlash('info', 'Fire in the hole !');
        return $this->render('base.html.twig');
    }
}
