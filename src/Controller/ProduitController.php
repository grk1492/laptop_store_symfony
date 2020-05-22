<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\AppService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Class ProduitController
 * @package App\Controller
 * @Route("/admin/produit", name="produit_")
 * @IsGranted("ROLE_ADMIN")
 */

class ProduitController extends AbstractController
{
    /**
     * 
     * @Route("/", name="liste")
     * @param AppService $service
     * @param Request $request
     * @return Response
     */
    public function liste(AppService $service, Request $request)
    {
        
        return $this->render(
            'produit/liste.html.twig',
            [
                'titrePage' => $service->getTitre("Les Produits de l'enseigne"),
                'produits' => $service->getListeProduit($request),
                'lignesCmds' => $service->contenuDuPanier(),
                'total' => $service->getTotalPanier()
            ]
        );
    }

    /**
     * 
     * @Route("/ajouter", name="ajouter", methods={"GET","POST"})
     * @param Request $request
     * @param AppService $service
     * @return Response
     * @return RedirectResponse|Response
     */
    public function ajouter(Request $request, AppService $service)
    {
        // new instance de la Class produit
        $produit = new Produit();

        //On crée le form avec la class ProduitType
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        //check si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            //on récupère le nom saisi
            $nom = $form->get("nom")->getData();

            //On met le nom en majuscule
            $produit->setNom($service->capitalize($nom));
            $produit->setMajLe(new DateTime());
            //on persiste en db
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            //on redirige vers la liste
            return $this->redirectToRoute("produit_liste");
        }
        return $this->render("produit/edit.html.twig", [
            'titrePage' => $service->getTitre("Ajout des produits"),
            'produit' => $produit,
            'form' => $form->createView(),
            'lignesCmds' => $service->contenuDuPanier(),
            'total' => $service->getTotalPanier()
        ]);
    }

    /**
     * 
     *
     * @param Produit $produit
     * @param Request $request
     * @param AppService $service
     * @Route("/{id}/modifier", name="modifier", methods={"GET","POST"})
     */
    public function modifier(Produit $produit, Request $request, AppService $service)
    {
      
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        //check si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom saisi
            $nom = $form->get("nom")->getData();
            //On met le nom en majuscule
            $produit->setNom(mb_strtoupper($nom));

            //on persiste en db
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            //on redirige vers la liste
            return $this->redirectToRoute("produit_liste");
        }
        return $this->render("produit/edit.html.twig", [
            'produit' => $produit, 
            'form' => $form->createView(),
            'titrePage' => $service->getTitre("Modification des produits"),
            'lignesCmds' => $service->contenuDuPanier(),
            'total' => $service->getTotalPanier()
           
        ]);
    }

    /**
     * @param Produit $produit
     * @param Request $request
     * @Route("/{id}/supprimer", name="supprimer")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();

        $produit = $em->getRepository(Produit::class)->find($id);

        if (!$produit) {
            return $this->redirectToRoute('produit_liste');
        }

        $em->remove($produit);
        $em->flush();

        return $this->redirectToRoute('produit_liste');
    }

}
