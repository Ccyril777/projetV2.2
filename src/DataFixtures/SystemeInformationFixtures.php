<?php

namespace App\DataFixtures;

use App\Entity\SystemeInformation;
use App\Entity\Confidentialite;
use App\Entity\Domaine;
use App\Entity\TypologyMI;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

use Faker;

class SystemeInformationFixtures extends Fixture //implements LoggerAwareInterface
{

    //public LoggerInterface $mylog;

    public function load(ObjectManager $manager)
    {
        //$this->mylog->info("Début de la fixture SystemeInformation !");

        $faker  =  Faker\Factory::create('fr_FR');

        $confidentialites =[];
        for($i=1;$i<=5;$i++) {
        $confidentialites[$i] = new Confidentialite();
        $confidentialites[$i]->setConfidentialiteName($faker->lastname);
        $manager->persist($confidentialites[$i]);
        }

        $domaines=[];
        for($i=1;$i<=10;$i++) {
        $domaines[$i] = new Domaine();
        $domaines[$i]->setDomaineName($faker->firstname);
        $manager->persist($domaines[$i]);
        }

        $types=[];
        for($i=1;$i<=10;$i++) {
        $types[$i] = new TypologyMI();
        $types[$i]->setShortName($faker->firstname);
        $types[$i]->setLongName($faker->name);
        $manager->persist($types[$i]);
        }

//première création des SI
        for ($i = 1; $i <= 30; $i++) {
            $alea = $faker->numberBetween(1,count($confidentialites));
            $maconfidentialite = $confidentialites[$alea];
            $alea = $faker->numberBetween(1,count($domaines));
            $mondomaine = $domaines[$alea];
            $alea = $faker->numberBetween(1,count($types));
            $montype = $types[$alea];

            $systemeInformations[$i] = new systemeInformation();
            $systemeInformations[$i]->setUsualName($faker->firstname);
            $systemeInformations[$i]->setSiiName($faker->lastname);
            $systemeInformations[$i]->setConfidentialite($maconfidentialite);
            $systemeInformations[$i]->setDomaine($mondomaine);
            $systemeInformations[$i]->setType($montype);

            $manager->persist($systemeInformations[$i]);
        }

        //mise en relation des SI avec leur SI Support piochés aléatoirement
        for($i=1;$i<=count($systemeInformations);$i++){
            $monSI = $systemeInformations[$i];
            //copier Array $systemeInformations dans un autre
            //https://stackoverflow.com/questions/1532618/is-there-a-function-to-make-a-copy-of-a-php-array-to-another
            //

// PHP will copy the array by default. References in PHP have to be explicit.

// $a = array(1,2);
// $b = $a; // $b will be a different array
// $c = &$a; // $c will be a reference to $a


            $systemeInformationsCopie = $systemeInformations;

            $alea = $faker->numberBetween(1,count($systemeInformationsCopie));
            $monSI->addSystemeSupport($systemeInformationsCopie[$alea]);
            unset($systemeInformationsCopie[$alea]);
        }

        $manager->flush();
    }
}
