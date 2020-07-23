<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class RegionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR_fr');


        for ($i = 0; $i < 10; $i++) {

            $region = new Region();
            $departement = new Departement();
            $departement->setCode($faker->countryCode);
            $departement->setNom($faker->streetAddress);
            $region
                ->setNom($faker->city);
            $region->setCode($faker->countryCode);
            $region->addDepartement($departement);
            $manager->persist($departement);
            $manager->persist($region);
        }
        $manager->flush();
    }
}
