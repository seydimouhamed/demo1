<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Niveau;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Competence;
use App\Entity\ProfilSortie;
use App\Entity\GroupeCompetence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CompetenceFixtures extends Fixture
{

    public function __construct()
    {

     }
    


    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');
        $tab_niveau=[];
        for($i=1;$i<=3;$i++)
        {
            $niveau=new Niveau();
            $niveau->setLibelle('libelle '.$i);
            $niveau->setCritereEvaluation('critere_evaluation '.$i);
            $niveau->setGroupeAction('groupe action '.$i);
            
            $tab_niveau[]=$niveau;
            $manager->persist($niveau);
        }
            $manager->flush();

        $tab_competence=[];
        for($i=1; $i<=10;$i++)
        {
            $competence=new Competence();
            $competence->setLibelle('libelle_'.$i);
            $competence->setNiveau($fake->randomElement($tab_niveau));

            $manager->persist($competence);
        }
        $manager->flush();

        $grpc=new GroupeCompetence();
        $grpc->setLidelle($fake->realText($maxNBChars = 50, $indexSize = 2 ));
        $grpc->setDescription($fake->text);
        // $grpc->setAdresse($fake->address());

        $manager->persist($grpc);
        $manager->flush();
      

    }
    
}
