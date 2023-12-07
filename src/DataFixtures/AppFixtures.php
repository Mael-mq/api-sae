<?php

namespace App\DataFixtures;

use App\Entity\CoursApp;
use App\Entity\Instrument;
use App\Repository\InstrumentRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $instruments = [
            "Guitare",
            "Basse",
            "Batterie",
            "Piano",
            "Violon",
            "Violoncelle",
            "Flûte",
            "Saxophone",
            "Trompette",
            "Trombone",
            "Harpe",
            "Accordéon",
            "Orgue",
            "Synthétiseur",
            "Chant"
        ];

        $listInstruments = [];
        for($i=0; $i < 15; $i++){
            $instrument = new Instrument;
            $instrument->setName($instruments[$i]);
            $manager->persist($instrument);

            $listInstruments[] = $instrument;
        }

        $difficulties = ["Facile", "Moyen", "Difficile"];
        for($c=0; $c < 15; $c++){
            
            $coursApp = new CoursApp;
            $coursApp->setTitle($faker->sentence(6))
                ->setInstrument($listInstruments[array_rand($listInstruments)])
                ->setDifficulty($difficulties[mt_rand(0,2)])
            ;
            $manager->persist($coursApp);
        }

        $manager->flush();
    }
}
