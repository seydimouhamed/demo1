<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $encoder;
    private $serializer;
    private $validator;
    private $em;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em)
    {
        $this->encoder=$encoder;
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->em=$em;
    }
    /**
     * @Route(
     *     name="getProfilSortie",
     *     path="/api/admin/profilSprties",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::getProfilSortie",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="get_profil_sortie"
     *     }
     * )
     */
    public function getProfilSortie(ProfilSortieRepository $repo)
    {
        $user= $repo->findByArchivage(0);
        // $user=$this->serializer->serialize($user,"json");
        return $this->json($user,200);
    }
    /**
     * @Route(
     *     name="archive_profil",
     *     path="/api/admin/profilSorties/{id}",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::archiveProfil",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="archive_profilSortie"
     *     }
     * )
     */
    public function archiveProfil(ProfilSortieRepository $repo,$id)
    {
        $profil=$repo->find($id)
                  ->setArchivage(1);
        $this->em->persist($profil);
        $this->em->flush();
        // $user=$this->serializer->serialize($user,"json");
        return $this->json(true,200);
    }
}
