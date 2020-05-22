<?php

namespace App\Controller;

use Exception;
use App\Service\AppService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * class SecurityController
 * @package App\Controller
 * @Route("/", name="app_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @param AppService $service
     * @return Response 
     */
    public function login(AuthenticationUtils $authenticationUtils, AppService $service)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'titrePage' => $service->getTitre("Login page"),
            'lignesCmds' => $service->contenuDuPanier(),
            'total' => $service->getTotalPanier(),
            'last_username' => $lastUsername,
            'error'=> $error,
        ]);
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return new Exception("Sera intercepté avant d'arriver ici");
    }
}
