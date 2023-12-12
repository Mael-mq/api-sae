<?php

namespace App\DataFixtures;

use App\Entity\CoursApp;
use App\Entity\Instrument;
use App\Entity\User;
use App\Repository\InstrumentRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // Création d'un user "normal"
        $user = new User();
        $user->setEmail("user@mmi.fr");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);
        
        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@mmi.fr");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);

        $userTeacher = new User();
        $userTeacher->setEmail("teacher@mmi.fr");
        $userTeacher->setRoles(["ROLE_USER", "ROLE_TEACHER"]);
        $userTeacher->setPassword($this->userPasswordHasher->hashPassword($userTeacher, "password"));
        $manager->persist($userTeacher);

        $userStudent = new User();
        $userStudent->setEmail("student@mmi.fr");
        $userStudent->setRoles(["ROLE_USER", "ROLE_STUDENT"]);
        $userStudent->setPassword($this->userPasswordHasher->hashPassword($userStudent, "password"));
        $manager->persist($userStudent);

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
