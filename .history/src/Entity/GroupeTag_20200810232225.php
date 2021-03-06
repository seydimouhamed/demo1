<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *           "get_grptags"={ 
 *               "method"="GET", 
 *               "path"="/admin/grptags",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "add_grptags"={ 
 *               "method"="POST", 
 *               "path"="/admin/grptags",
 *          }
 *      },
 *      itemOperations={
 *           "get_grptag_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/grptags/{id}",
 *                "defaults"={"id"=null},
 *          },
 *
 *            "update_grptag_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/grptags/{id}",
 *          },
 *      },
 *       normalizationContext={"groups"={"grptag:read"}},
 *       denormalizationContext={"groups"={"grptag:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=10}
 * )
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 */
class GroupeTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"grptag:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"grptag:read","grptag:write"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags")
     * @Groups({"grptag:read"})
     * @ApiSubresource
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }
}
