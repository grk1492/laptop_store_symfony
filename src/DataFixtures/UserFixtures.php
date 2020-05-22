<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @param UserPasswordEncoderInterface $userPassword
     */
    public function __construct(UserPasswordEncoderInterface $userPassword)
    {
        $this->userPassword = $userPassword;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 0; $i < 15; $i++) {

            $user = new User();

            //permet un email unique 
            $user->setPrenom($faker->unique(true)->firstName);
            $user->setEmail($faker->unique(true)->email);
            $user->setPassword($this->userPassword->encodePassword(
                $user,'mdp123'
            ));

            //persist les data dans la db
            $manager->persist($user);
        }
        $manager->flush();
    }
}
