<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @IsGranted("ROLE_USER")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route(path="/article", name="articles")
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $session = $request->getSession();
        $session->get("categories");
        dump($session->get("categories"));
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
            try {
                // Les données récupérées par le formulaire sont transférées à notre article
                $article = $form->getData();
                // On donne la date à notre article qui est l'information manquante dans le formulaire
                // $article->setCreatedAt(new \DateTime);

                // Pour traiter l'image, on doit la récupérer
                $image = $article->getImage();
                // On créé un nouveau pour l'image afin d'éviter les conflits.
                // On utilise la méthode guessExtension() de HttpFoundation\File pour connaitre l'extension du fichier (.jpeg, .png, .pdf, ...)
                $imageName = md5(uniqid()).'.'.$image->guessExtension();
                // La méthode move est une méthode de HttpFoundation qui permet de déplacer l'image dans un dossier
                // Elle prend 2 paramètres: Le premier est le chemin vers le dossier de stockage, le deuxième est le nouveau nom du fichier qui sera stocké
                // Pour définir le chemin, on utilise un paramètre de configuration que l'on définit dans config/services.yaml
                $image->move(
                    $this->getParameter('upload_files'),
                    $imageName
                );
                // Une fois le fichier déplacé, on enregistre le nouveau nom de celui-ci dans la base de données.
                $article->setImage($imageName);

                // On appelle notre manager
                $manager = $this->getDoctrine()->getManager();
                // On stocke notre article en mémoire tampon pour pouvoir l'enregistrer en BDD par la suite
                $manager->persist($article);
                // On stocke l'article dans la BDD, on vide la mémoire et on récupère toutes les infos de l'article
                $manager->flush();
                
                // $this->addFlash('success', "L'enregistrement a réussi!");

                return $this->redirectToRoute("singleArticle", [
                    'id' => $article->getId()
                ]);

            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());

                return $this->redirectToRoute("articles");
            }
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

    /**
     * @Route(path="/article/{id}/update", name="updateArticle")
     */
    public function update(Article $article, Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        
        if ($article->getImage() !== null) {
            $article->setImage( new File($this->getParameter('upload_files').'/'.$article->getImage()));
        }
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            if ($article->getImage() !== null) {
                // Pour traiter l'image, on doit la récupérer
                $image = $article->getImage();
                // On créé un nouveau pour l'image afin d'éviter les conflits.
                // On utilise la méthode guessExtension() de HttpFoundation\File pour connaitre l'extension du fichier (.jpeg, .png, .pdf, ...)
                $imageName = md5(uniqid()).'.'.$image->guessExtension();
                // La méthode move est une méthode de HttpFoundation qui permet de déplacer l'image dans un dossier
                // Elle prend 2 paramètres: Le premier est le chemin vers le dossier de stockage, le deuxième est le nouveau nom du fichier qui sera stocké
                // Pour définir le chemin, on utilise un paramètre de configuration que l'on définit dans config/services.yaml
                $image->move(
                    $this->getParameter('upload_files'),
                    $imageName
                );
                // Une fois le fichier déplacé, on enregistre le nouveau nom de celui-ci dans la base de données.
                $article->setImage($imageName);
            }

            $manager = $this->getDoctrine()->getManager();

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute("singleArticle", [
                'id' => $article->getId()
            ]);
        }

        return $this->render("article/update.html.twig", [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * @Route(path="/admin/article/{id}/delete", name="deleteArticle")
     */
    public function delete (Article $article)
    {
        if ($article) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($article);
            $manager->flush();
        }

        return $this->redirectToRoute("articles");
    }
}
