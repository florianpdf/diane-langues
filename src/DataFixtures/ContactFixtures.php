<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\WebsiteConfig;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $contact = new Contact();
        // $contact->setName("Grandjean");
        // $contact->setFirstname("Florian");
        // $contact->setPhoneNumber("0669972880");
        // $contact->setEmail("florian.pdf@gmail");

        // $manager->persist($contact);

        // $manager->flush();
    }
}
