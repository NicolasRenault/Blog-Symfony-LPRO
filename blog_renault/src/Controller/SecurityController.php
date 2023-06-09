<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
    * @Route("/{_locale}/login", 
    *  name="security_login")
    *
    * @return Response
    */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        // Récupération des erreurs s'il y en a eu lors de la précédente authentification
        $error = $authUtils->getLastAuthenticationError();
        // Login précédemment saisi par l'utilisateur
        $lastUsername = $authUtils->getLastUsername();
        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
    * @Route("/{_locale}/login_check", 
    * name="security_login_check")
    *
    * @return Response
    * @throws \Exception
    */
    public function loginCheckAction()
    {
        throw new \Exception('Unexpexted loginCheck action');
    }


    /**
    * @Route("/{_locale}/logout", 
    *  name="security_logout")
    *
    * @return Response
    * @throws \Exception
    */
    public function logoutAction()
    {
        throw new \Exception('Unexpected logout action');
    }
}
