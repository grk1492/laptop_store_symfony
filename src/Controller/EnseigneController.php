<?php

namespace App\Controller;

use App\Entity\Enseigne;
use App\Form\EnseigneType;
use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

    


/**
 * Class EnseigneController
 * @package App\Controller
 * @Route("/admin/enseigne", name="enseigne_")
 * @IsGranted("ROLE_ADMIN")
 */

class EnseigneController extends AbstractController
{
    /**
     * 
     *
     * @Route("/", name="liste")
     * @param Request $request
     * @param AppService $service
     * @return Response
     */

    //version refactoriser avec la class AppService
    public function liste(Request $request, AppService $service)
    {
        //permet de proteger via les roles 
       // $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render(
            'enseigne/liste.html.twig',
            [
                'titrePage' => $service->getTitre("Les titres des enseignes"),
                'enseignes' => $service->getListeEnseigne($request),
                'lignesCmds' => $service->contenuDuPanier(),
                'total' => $service->getTotalPanier()
            ]
        );
    }

    /**
     *@Route("/ajouter", name="ajouter", methods={"GET","POST"})
     *@param Request $request
     *@param AppService $service
     */
    public function ajouter(Request $request, AppService $service)
    {
        
        $enseigne = new Enseigne();
        //On crée le form avec la class EnseigneType
        $form = $this->createForm(EnseigneType::class, $enseigne);
        $form->handleRequest($request);

        //check si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom saisi
            $nom = $form->get("nom")->getData();
            //On met le nom en majuscule
            $enseigne->setNom($service->capitalize($nom));

            //on persiste en db
            $em = $this->getDoctrine()->getManager();
            $em->persist($enseigne);
            $em->flush();

            //on redirige vers la liste
            return $this->redirectToRoute("enseigne_liste");
        }
        return $this->render("enseigne/edit.html.twig", [
            'enseigne' => $enseigne, 
            'form' => $form->createView(),
            'titrePage' => $service->getTitre("Ajout des enseignes"),
            'lignesCmds' => $service->contenuDuPanier(),
            'total' => $service->getTotalPanier()
           
        ]);
    }

    /**
     * 
     *
     * @param Enseigne $enseigne
     * @param Request $request
     * @param AppService $service
     * @Route("/{id}/modifier", name="modifier", methods={"GET","POST"})
     */
    public function modifier(Enseigne $enseigne, Request $request, AppService $service)
    {
      
        $form = $this->createForm(EnseigneType::class, $enseigne);
        $form->handleRequest($request);

        //check si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom saisi
            $nom = $form->get("nom")->getData();
            //On met le nom en majuscule
            $enseigne->setNom(mb_strtoupper($nom));

            //on persiste en db
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            //on redirige vers la liste
            return $this->redirectToRoute("enseigne_liste");
        }
        return $this->render("enseigne/edit.html.twig", [
            'enseigne' => $enseigne, 
            'form' => $form->createView(),
            'titrePage' => $service->getTitre("Modification des enseignes"),
            'lignesCmds' => $service->contenuDuPanier(),
            'total' => $service->getTotalPanier()
        ]);
    }

    /**
     * 
     *
     * @param Enseigne $enseigne
     * @param Request $request
     * @Route("/{id}/supprimer", name="supprimer")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();

        $enseigne = $em->getRepository(Enseigne::class)->find($id);

        if (!$enseigne) {
            return $this->redirectToRoute('enseigne_liste');
        }

        $em->remove($enseigne);
        $em->flush();

        return $this->redirectToRoute('enseigne_liste');
    }

    
}
