<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController {

    // Découverte de l'affiche avec la class Response
    // public function hello ()
    // {
    //     $hello = "Hello Wordl!";
    //     return new Response($hello);
    // }

    /**
     * @Route(path="/", name="hello")
     */
    public function hello ()
    {
        return $this->render("hello.html.twig");
    }


    /**
     * Undocumented function
     * @Route(path="/bye", name="bye")
     * @return Response
     */
    public function bye (): Response
    {
        $bye = "Bye bye!";
        return new Response($bye);
    }

    public function addition ($nb1, $nb2)
    {
        if (is_int($nb1) && is_int($nb2)) {
            return $nb1 + $nb2;
        } else {
            return $nb1 . $nb2;
        }
    }
}

// class Test {

//     public function coucou (){}
// }

// $test = new Test();
// $test->coucou();