<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Brief;
use App\Entity\Niveau;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Promotion;
use App\Entity\Commentaire;
use App\Entity\Referentiel;
use App\Entity\BriefMaPromo;
use App\Entity\FilDiscution;
use App\Entity\BriefApprenant;
use App\Entity\LivrablePartiels;
use App\DataFixtures\LivrableFixtures;
use App\Entity\AprenantLivrablePartiel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BriefFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct()
    {

     }
    
    
     public function getDependencies()
     {
         return array(
             LivrableFixtures::class,
         );
     }

    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');

        $formateurs=$manager->getRepository(Formateur::class)->findAll();
        $tab_formateurs=[];
        foreach($formateurs as $ref)
        {
            $tab_formateurs[]=$ref;
        }

        //recuperations de apprenants
        $apprenants=$manager->getRepository(Apprenant::class)->findAll();
        
         

        $referentiels=$manager->getRepository(Referentiel::class)->findAll();
        $tab_referentiels=[];
        foreach($referentiels as $ref)
        {
            $tab_referentiels[]=$ref;
        }
        $tags=$manager->getRepository(Tag::class)->findAll();
        $tab_tags=[];
        foreach($tags as $tag)
        {
            $tab_tags[]=$tag;
        }
//Promotion
        $promos=$manager->getRepository(Promotion::class)->findAll();

        $niveaux=$manager->getRepository(Niveau::class)->findAll();
        $tab_niveaux=[];
        foreach($niveaux as $niv)
        {
            $tab_niveaux[]=$niv;
        }

        $tab_briefs=[];
        for($i=1;$i<=10;$i++)
        {
            $brief=new Brief();
            $brief->setContexte("context brief ".$i);
            $brief->setArchivage(false)
                  ->setTitre('Titre '.$i)
                  ->setCricterePerformance('Criteres de performance '.$i)
                  ->setDateLimite($fake->datetime)
                  ->setDatePoste($fake->datetime)
                  ->setDescriptionRapide($fake->text)
                  ->setFormateur($fake->randomElement($tab_formateurs))
                  ->setEtat('etat '.$i)
                  ->setLangue('francais')
                  ->setListeLivrable('liste livrables '.$i)
                  ->setModaliteDevaluation('Modalité d evaluation '.$i)
                  ->setModalitePedagogique('Modalité pédagoique '.$i)
                  ->setReferentiel($fake->randomElement($tab_referentiels))
                  ->setStatut($fake->randomElement(['brouillon','valide','assigne']));
                   for($j=1;$j<=5;$j++)
                   {
                        $brief->addNiveau($fake->unique()->randomElement($tab_niveaux));
                        $brief->addTag($fake->unique()->randomElement($tab_tags));
                              
                   }
                   $tab_briefs[]=$brief;
                  $fake->unique($reset = true );
                  $manager->persist($brief);
        }
      $manager->flush();

      //-------------------//
      //    BRIEF PROMO    //
      //-------------------//
        $tab_briefPromo=[];
        for($i=1;$i<=5;$i++)
        {
            $briefPromo=new BriefMaPromo();
            $briefPromo->setBrief($fake->unique()->randomElement($tab_briefs));
            $briefPromo->setPromo($fake->randomElement($promos));

            $tab_briefPromo[]=$briefPromo;
            $manager->persist($briefPromo);
        }
        $manager->flush();

        //--------------------------//
        //   BRIEF PROMO APPRENANT  //
        //--------------------------//
      for($a=0;$a<4;$a++)
      {
          $apprt=$fake->unique()->randomElement($apprenants);
            for($i=0;$i<5;$i++)
            {
                $briefappr=new BriefApprenant();
                $briefappr->setApprenant($apprt);
                $briefappr->setStatut($fake->randomElement(['valide','rendu','assigne']));
                $briefappr->setBriefPromo($tab_briefPromo[$a]);

                $manager->persist($briefappr);
            }
            $manager->flush();
          
      }

      $tab_livrablePartiel=[];
      foreach($tab_briefPromo as $k => $tbp)
      {
            for($i=0;$i<4;$i++)
            {
                $lp=new LivrablePartiels();
                $lp->setDelai($fake->datetime)
                    ->setType($fake->randomElement(['ISAS','Code','modelisation']))
                    ->setDateCreation($fake->datetime)
                    ->setDescription($fake->text)
                    ->setEtat($fake->randomElement(['rendu','valide','invalide','assigne']))
                    ->setLibelle('libelle promo'.$k. '_'.$i)
                    ->setNbreCorriger($fake->numberBetween($min = 10, $max = 20))
                    ->setNbreRendu($fake->numberBetween($min = 20, $max = 30))
                    ->setBriefMaPromo($tbp);

                    $tab_livrablePartiel[]=$lp;

                $manager->persist($lp);
                  
            }

            $manager->flush();
      }

//Apprenant livrable partiel 

    //   $tab_apprLivrablePartiel=[];
    //   for($i=0;$i<5;$i)
    //   {
    //         $applp=new AprenantLivrablePartiel();
    //         // >setApprenant($fake->randomElement($apprenants))
    //         //     ->setLivrablePartiel($fake->randomElement($tab_livrablePartiel))
    //             $applp->setDelai($fake->datetime)
    //             ->setEtat($fake->randomElement(['rendu','assigne','valide','invalide']));
    //            // $tab_apprLivrablePartiel[]=$applp;
    //         $manager->persist($applp);
    //   }
    //   $manager->flush();


//fil de discution
    //   for($i=0;$i<5;$i++)
    //   {
    //         $fildisc=new FilDiscution();
    //         $fildisc->setApprenantLivrablePartiel($tab_apprLivrablePartiel[$i]);
                    

    //            //$formateur=$fake->randomElement($tab_formateurs);
    //         // for($c=0;$c<5;$c++)
    //         // {
    //         //     $comment=new Commentaire();
    //         //     $commentateur_forma=$fake->randomElement([true,false]);
    //         //     $comment->setCreatAt($fake->datetime)
    //         //             ->setDescription($fake->text);
    //         //     if($commentateur_forma)
    //         //     {
    //         //         $comment->setFormateur($formateur);
    //         //     }
    //         //     $manager->persist($comment);

    //         //   $fildisc->addCommentaire($comment);
    //         // }
        
    //      $manager->persist($fildisc);
    //   }
    //     $manager->flush();
    
    }
    
}
