<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i=1; $i < 6; $i++) { 
            $categorie = new Categorie;
            $categorie->setName("categorie n°$i");
            $manager->persist($categorie);
        }
        for ($i=1; $i < 20; $i++) { 
            $article = new Article;
            $article->setTitre($faker->sentence(4))
                    ->setContenu($faker->paragraph(10))
                    ->setCreatedAt(new \DateTime());
            $manager->persist($article);
        }
        $manager->flush();
    }
}
