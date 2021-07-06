<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route(path="/categorie", name="categorie")
     * @return Response
     */
    public function index()
    {
        // $categories = [
        //     ['name' => 'categorie n°1'],
        //     ['name' => 'categorie n°2'],
        //     ["name" => "categorie n°3"],
        //     ["name" => "categorie n°4"]
        // ];

        // Pour récupérer les catégories, je dois utiliser une méthode de AbstractController
        // qui est getDoctrine pour me connecter au gestionnaire de BDD doctrine.
        // Une fois connecté, getDoctrine retourne un ManagerRegistry qui est la classe qui gère nos requêtes
        // Une fois connecté à cette classe, on doit se connecter au repository qui nous permet de faire nos requêtes SELECT
        // On utilise donc la méthode getRepository(). 
        // Cette méthode prend un paramètre qui est l'entité dont on veut récupérer les informations.
        // On appelle ensuite la méthode dont on a besoin et qui est sur notre repository.
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/index.html.twig', [
            "controller_name" => 'CategorieController',
            // "nomDelaVariable" => "valeurDeLaVariable",
            "categories" => $categories
        ]);
    }
}
