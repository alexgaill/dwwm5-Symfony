<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    /**
     * @Route(path="/categorie", name="categories")
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

    /**
     * @Route(path="/categorie/save", name="saveCategorie")
     */
    public function create(Request $request)
    {
        // dump($request);
        $categorie = new Categorie;
        // $categorie->setName("Categorie de controller3");
        $form = $this->createFormBuilder($categorie)
                    ->add('name', TextType::class, [
                        'label' => "Nom de la catégorie",
                        'attr' => [
                            'placeholder' => "catégorie n°X"
                        ]
                    ])
                    ->add("Enregistrer", SubmitType::class)
                    ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            // dump($categorie);
        
            $manager = $this->getDoctrine()->getManager();
            
            $manager->persist($categorie);
            $manager->flush();
            // return $this->redirectToRoute("categories");
            return $this->redirectToRoute("singleCategorie", [
                'id' => $categorie->getId()
            ]);
        }

        return $this->render("categorie/save.html.twig", [
            'form' => $form->createView()
        ]);
    }
    // Toujours mettre save avant single si le path se ressemble
    // "/categorie/save" peut être compris comme /categorie/id=save

    /**
     * @Route(path="/categorie/{id}", name="singleCategorie")
     */
    public function single (Categorie $categorie)
    {
        return $this->render("categorie/single.html.twig", [
            "categorie" => $categorie
        ]);
    }

    
}
