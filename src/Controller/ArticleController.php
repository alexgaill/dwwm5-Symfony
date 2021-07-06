<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route(path="/article", name="article")
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
