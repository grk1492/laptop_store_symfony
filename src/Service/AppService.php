<?php
//ne pas oublié le namespace
namespace App\Service;

use App\Entity\LigneDeCmd;
use App\Repository\EnseigneRepository;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AppService
{
    /**
     * @var EnseigneRepository
     */
    private $enseigneRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var ProduitRepository
     */
    private $produitRepository;

    /**
     * @var SessionInterface 
     */
    private $session;

    /**
     * AppService Constructor
     * @param EnseigneRepository $enseigneRepository
     * @param PaginatorInterface $paginator
     * @param ProduitRepository $produitRepository
     * @param SessionInterface $session
     */
    public function __construct(EnseigneRepository $enseigneRepository, ProduitRepository $produitRepository, PaginatorInterface $paginator, SessionInterface $session)
    {
        $this->enseigneRepository = $enseigneRepository;
        $this->paginator = $paginator;
        $this->produitRepository = $produitRepository;
        $this->session = $session;
    }

    public function capitalize(string $mot)
    {
        return ucwords(mb_strtoupper($mot));
    }

    public function uppercase(string $mot)
    {
        return mb_strtoupper($mot);
    }

    public function getTitre(string $titre)
    {
        return $titre;
    }

    //methode qui récupèrent les enseignes
    public function getListeEnseigne(Request $request)
    {
        //$donnees récuperent toutes les enseignes
        $donnees = $this->enseigneRepository->findAll();

        //$pagination gère le systeme de pagination
        $enseignes = $this->paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5 //limit d'affichage par page /5 !
        );

        return $enseignes;
    }

    public function getListeProduit(Request $request)
    {
        //$donnees récuperent toutes les enseignes
        $donnees = $this->produitRepository->findAll();

        //$pagination gère le systeme de pagination
        $produits = $this->paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5 //limit d'affichage par page /5 !
        );

        return $produits;
    }

    //affichera la liste des produits sur la page accueil
    public function getListeProduitAccueil(Request $request)
    {
        //$donnees récuperent toutes les enseignes
        $donnees = $this->produitRepository->findAll();

        //$pagination gère le systeme de pagination
        $produits = $this->paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            3 //limit d'affichage par page /5 !
        );

        return $produits;
    }

    public function ajouterPanier(int $id)
    {
        //on défini le panier à récupérer
        $panier = $this->session->get('panier', []);

        //ajout un produit si il n'existe pas ou il l'incrémente
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {

            $panier[$id] = 1;
        }


        //toujours remettre le panier dans la session apres modif!!!
        $this->session->set('panier', $panier);
    }

    public function contenuDuPanier():array
    {
          //$session = $request->getSession();
          $panier = $this->session->get('panier',[]);
        
          $contenuDuPanier = [];
          foreach ($panier as $id => $quantite) {
             $ldc = new LigneDeCmd($quantite, $this->produitRepository->find($id));
             $contenuDuPanier[] = [
                    'ligne_cmd' => $ldc
             ];
          }
          return $contenuDuPanier;

          
    }

    public function supprimerDuPanier(int $id)
    {
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }

        //on remet le panier en session apres chaque modif !!!!
        $this->session->set('panier', $panier);
    }

    public function getTotalPanier()
    {
        //récupération contenu panier
        $items = $this->contenuDuPanier();
        
        $total=0;
        foreach ($items as $item) {
            $sous_total = $item['ligne_cmd']->getSousTotal();
            $total+= $sous_total;
        }
        return $total;
    }

}
