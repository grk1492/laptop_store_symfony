<?php

namespace App\Controller;

use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * class StoreController
 * @package App\Controller
 * @Route("/", name="store_")
 */
class StoreController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     * @param AppService $service
     * @param Request $request
     * @return Response
     */
    public function accueil(AppService $service, Request $request)
    {
        return $this->render('store/accueil.html.twig', [
            'titrePage' => $service->getTitre('Laptop Store'),
            'produits' => $service->getListeProduitAccueil($request),
            'lignesCmds' => $service->contenuDuPanier(),
            'total' => $service->getTotalPanier(),
            
            
        ]);
    }
}
