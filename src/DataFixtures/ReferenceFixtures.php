<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Reference;

class ReferenceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < 3; $i++) { 
            $reference = new Reference();
            $reference->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quis mauris mauris. Nam leo felis, luctus sed nulla tincidunt, pulvinar volutpat ex. Curabitur accumsan ex eu purus vulputate, in condimentum. " . $i);
            $manager->persist($reference);
        }
        
        $manager->flush();
    }
}
