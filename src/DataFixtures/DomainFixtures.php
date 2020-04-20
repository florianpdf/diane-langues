<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Domain;
use Symfony\Component\HttpFoundation\File\File;

class DomainFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $content = [
        //     [
        //         "type" => "Post-édition",
        //         "description" => "La post-édition est le processus par lequel les humains modifient la traduction générée par machine pour obtenir un produit final acceptable. Une personne qui post-édite s'appelle un post-éditeur. Le concept de post-édition est lié à celui de pré-édition.",
        //         "image" => new File("/var/www/html/diane-langues/public/uploads/images/services/service_relecture.jpg"),
        //         "updated_at" => new \DateTime()
        //     ], [
        //         "type" => "Relecture",
        //         "description" => "La relecture est une activité consistant à relire un texte. Elle peut avoir différentes motivations. Pour son auteur, avant la publication du texte, elle peut servir à détecter d'éventuelles erreurs",
        //         "image" => new File("/var/www/html/diane-langues/public/uploads/images/services/service_relecture.jpg"),
        //         "updated_at" => new \DateTime()
        //     ], [
        //         "type" => "Traduction",
        //         "description" => "La traduction est le fait de faire passer un texte rédigé dans une langue dans une autre langue. Elle met en relation au moins deux langues et deux cultures, et parfois deux époques.",
        //         "image" => new File("/var/www/html/diane-langues/public/uploads/images/services/service_relecture.jpg"),
        //         "updated_at" => new \DateTime()
        //         ]

        //     ];

        // foreach ($content as $value) {
        //     $domain = new Domain();
        //     $domain->setType($value['type']);
        //     $domain->setDescription($value['description']);
        //     $domain->setImageFile($value['image']);
        //     $domain->setImage("service_relecture.jpg");
        //     $manager->persist($domain);
        // }
        // $manager->flush();
    }
}