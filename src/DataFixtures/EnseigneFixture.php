<?php

namespace App\DataFixtures;

use App\Entity\Enseigne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class EnseigneFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i=0; $i < 15 ; $i++) { 

            $enseigne = new Enseigne();

            //permet un nom unique de "company"
            $enseigne->setNom($faker->unique(true)->company);

            //persist les data dans la db
            $manager->persist($enseigne);
        }
        $manager->flush();
    }
}
