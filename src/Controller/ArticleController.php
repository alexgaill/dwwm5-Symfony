<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route(path="/article", name="articles")
     *
     * @return Response
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route(path="/article/save", name="saveArticle")
     */
    public function create(Request $request)
    {
        // On instancie un nouvel article
        $article = new Article;
        // On appelle notre article type pour créer le formulaire
        $form = $this->createForm(ArticleType::class, $article);
        // On associe les informations récupérées dans $request à notre formulaire
        $form->handleRequest($request);

        // On vérifie que notre formulaire est soumis et que les informations récupérées sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Les données récupérées par le formulaire sont transférées à notre article
            $article = $form->getData();
            // On donne la date à notre article qui est l'information manquante dans le formulaire
            $article->setCreatedAt(new \DateTime);
            // On appelle notre manager
            $manager = $this->getDoctrine()->getManager();
            // On stocke notre article en mémoire tampon pour pouvoir l'enregistrer en BDD par la suite
            $manager->persist($article);
            // On stocke l'article dans la BDD, on vide la mémoire et on récupère toutes les infos de l'article
            $manager->flush();

            return $this->redirectToRoute("singleArticle", [
                'id' => $article->getId()
            ]);

        }

        return $this->render("article/save.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/article/{id}", name="singleArticle")
     *
     * @return void
     */
    public function single($id)
    {
        // Afficher les informations d'un article
        // Quel article est à charger? On le définit avec son id

        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render("article/single.html.twig", [
            "article" => $article
        ]);
    }
}
