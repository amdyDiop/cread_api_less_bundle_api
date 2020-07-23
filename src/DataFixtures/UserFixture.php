<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');


        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
            $user->setEmail($faker->email);
            $password = $this->encoder->encodePassword($user, "password");
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();
        }

    }
}