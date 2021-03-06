<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ApiResource(
 *     routePrefix="/admin",
 *      itemOperations={
 *                                  "get",
 *                                  "Statut_Groupe"={
 *                                      "method"="PUT",
 *                                        "path"="/admin/promo/{id}/groupes/{id2}" ,
 *                                      "route_name"="modifie_Statut_Groupe",
 *                                         "defaults"={"id"=null},
 *                                      "modifie_Statut_discontinuation",},
 *
 *
 *                                    "add_Apprenant"={
 *                                      "method"="PUT",
 *                                        "path"="/admin/promo/{id}/apprenants" ,
 *                                      "route_name"="add_promo_apprenant",
 *                                       "defaults"={"id"=null},
 *                                      "add_promo_discontinuation",},
 *
 *                                  "modifier_Promo"={
 *                                      "method"="PUT",
 *                                        "path"="/admin/promo/{id}" ,
 *                                      "route_name"="modifier_promo_id",
 *                                       "defaults"={"id"=null},
 *                                      "modifier_promo_discontinuation",},
 *
 *                                      "delete_Apprenant"={
 *                                      "method"="DELETE",
 *                                        "path"="/admin/promo/{id}/apprenants" ,
 *                                      "route_name"="delete_promo_apprenant",
 *                                       "defaults"={"id"=null},
 *                                      "add_promo_discontinuation",},
 *
 *                           "delete_Formateur"={
 *                                      "method"="DELETE",
 *                                        "path"="/admin/promo/{id}/formateur" ,
 *                                      "route_name"="delete_promo_formateur",
 *                                       "defaults"={"id"=null},
 *                                      "add_promo_discontinuation",},
 *
 *                                  "add_Formateur"={
 *                                      "method"="PUT",
 *                                        "path"="/admin/promo/{id}/Formateur" ,
 *                                      "route_name"="add_promo_formateur",
 *                                       "defaults"={"id"=null},
 *                                      "add_promo_discontinuation",},
 *
 *                      },
 *      normalizationContext={"groups"={"promo:read"}},
 *      denormalizationContext={"groups"={"promo:write"}}
 * )
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promo:read"})
     * @Groups({"groupe:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "promo:write"})
     * @Assert\NotBlank
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "promo:write"})
     * @Assert\NotBlank
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Groups({"promo:read", "promo:write"})
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "promo:write"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promo:read", "promo:write"})
     * @Assert\NotBlank
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promo:read", "promo:write"})
     * @Assert\NotBlank
     */
    private $dateFinPrvisoire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "promo:write"})
     * @Assert\NotBlank
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promo:read", "promo:write"})
     * @Assert\NotBlank
     */
    private $dateFinReelle;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"promo:read", "promo:write"})
     * @Assert\NotBlank
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Groupes::class, mappedBy="promotion")
     * @ApiSubresource()
     * @Groups({"promo:read"})
     *
     */
    private $groupes;

   

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"promo:read"})
     */
    private $avatar;


    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promotions")
     * @ApiSubresource()
     * @Groups({"promo:read"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promotions")
     * @ApiSubresource()
     * @Groups({"promo:read"})
     */
    private $referentiel;


    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinProvisoire(): ?\DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(?\DateTimeInterface $dateFinPrvisoire): self
    {
        $this->dateFinProvisoire = $dateFinPorvisoire;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateFinReelle(): ?\DateTimeInterface
    {
        return $this->dateFinReelle;
    }

    public function setDateFinReelle(?\DateTimeInterface $dateFinReelle): self
    {
        $this->dateFinReelle = $dateFinReelle;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Groupes[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupes $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromotion($this);
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getPromotion() === $this) {
                $groupe->setPromotion(null);
            }
        }

        return $this;
    }


    public function getAvatar()
    {
        // $data = stream_get_contents($this->avatar);
        // fclose($this->avatar);
return $this->avatar;
      // return base64_encode($data);
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }


    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
        }

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }

    

}