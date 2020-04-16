<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Service;

class ServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < 5; $i++) { 
            $service = new Service();
            $service->setType("Lorem ipsum " . $i);
            $service->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quis mauris mauris. Nam leo felis, luctus sed nulla tincidunt, pulvinar volutpat ex. Curabitur accumsan ex eu purus vulputate, in condimentum. " . $i);
            $service->setPrice($i . ",98€");
            $manager->persist($service);
        }
        
        $manager->flush();
    }
}
