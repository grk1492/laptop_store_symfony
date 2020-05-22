<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * class PanierController
 * @package App\Controller
 * @Route("/panier", name="panier_")
 */

class PanierController extends AbstractController
{
    /**
     * @Route("/", name="contenu")
     * @param SessionInterface $session
     * @param ProduitRepository $repository
     * @return Response
     */
    public function contenuDuPanier(AppService $service)
    {
        //on récupère le panier de la session
        //old way avec Request $request
       $contenuDuPanier = $service->contenuDuPanier();
        dd($contenuDuPanier);

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    /**
     * @Route("/ajouter/{id}", name="ajouter", methods={"GET", "POST"})
     * @param int $id
     * @param AppService $service
     * @return RedirectResponse
     */
    public function ajouter(int $id, AppService $service)
    {

        //ajout dans le panier via :id
        $service->ajouterPanier($id);
        
        return $this->redirectToRoute('store_accueil');
    }
    
    /**
     * @Route("/supprimer/{id}", name="supprimer", methods={"GET", "POST"})
     * @param int $id
     * @param AppService $service
     * @return RedirectResponse
     */
    public function supprimer(int $id, AppService $service)
    {
        $service->supprimerDuPanier($id);
        return $this->redirectToRoute("store_accueil");
    }
}
