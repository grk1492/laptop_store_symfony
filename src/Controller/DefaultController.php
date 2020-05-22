<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** Class DefaultController
 * @package App\Controller
 * @Route("/default", name="default_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        return $this->render("default.html.twig", [
            "message" => "Bonjour chez vous !!!"
        ]);
    }

    /**
     * @Route("/action", name="action")
     *
     * @return void
     */
    public function action()
    {
        return new Response("Je la 2eme action du controller");
    }

    //Pour passer des param dans l'URL et les récupéres
    /**
     * @param  $param
     * @Route("/action/{param}", name="param")
     */
    public function actionParam($param)
    {
        dd($param);
        return new Response("Je suis issue de actionParam");
    }
}
