<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
* @ORM\DiscriminatorColumn(name="discr", type="string")
* @ORM\DiscriminatorMap({"admin"="User","apprenant" = "Apprenant","formateur"="Formateur","cm"="CommunityManager"})
 * @ApiResource(
 *      collectionOperations={
 *           "get_admin_users"={ 
 *               "method"="GET", 
 *               "path"="/admin/users",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "add_users"={ 
 *               "method"="POST", 
 *               "path"="/admin/users",
 *                 "security_message"="Acces non autorisé",
 *          },
 *      },
 *      itemOperations={
 *           "get_admin_users_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/users/{id}",
 *                  "security_message"="Acces non autorisé",
 *                  "defaults"={"id"=null},
 *          },
 * 
 *            "modifier_admin_users_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/users/{id}",
 *                  "security_message"="Acces non autorisé",
 *                  "defaults"={"id"=null},
 *          },
 *      },
 *       normalizationContext={"groups"={"user:read"}},
 *       denormalizationContext={"groups"={"user:write"}},
 * attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user:read","profil:read","promo:read"})
     * @Groups({"getBriefByOneGroupeApp"})
     * @Groups({"getBriefBrouillonFormateur","getOnBriefOnePromo"})
     * @Groups({"brief:read"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     * 
     * @Groups({"user:read", "user:write","profil:read"})
     * @Groups({"groupe:read"})
     * @Groups({"promo:read"})
     * @Groups({"getBriefByOneGroupeApp"})
     * @Groups({"getBriefBrouillonFormateur","getOnBriefOnePromo"})
     * @Groups({"brief:read"})
     * @Assert\NotBlank
     */
    private $username;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     */
    private $password;

    /**
     * @Groups("user:write")
     * @Assert\NotBlank
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"user:read", "user:write", "profil:read"})
     * @Groups({"groupe:read"})
     * @Groups({"promo:read"})
     * @Groups({"getBriefByOneGroupeApp"})
     * @Groups({"getBriefBrouillonFormateur","getOnBriefOnePromo"})
     * @Groups({"brief:read"})
     *
     * @Assert\NotBlank
     */
    private $fisrtName;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     *  @Groups({"user:read", "user:write", "profil:read"})
     * @Groups({"groupe:read"})
     * @Groups({"promo:read"})
     * @Groups({"getBriefByOneGroupeApp"})
     * @Groups({"brief:read"})
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     *  @Groups({"user:read", "user:write", "profil:read"})
     * @Groups({"groupe:read"})
     * @Groups({"getBriefByOneGroupeApp"})
     * @Groups({"getBriefBrouillonFormateur","getOnBriefOnePromo"})
     * @Groups({"brief:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * 
     *  @Groups({"user:read", "user:write", "profil:read"})
     *  @Groups({"groupe:read"})
     *  @Groups({"promo:read"})
     * @Groups({"getBriefByOneGroupeApp"})
     * @Groups({"getBriefBrouillonFormateur","getOnBriefOnePromo"})
     * @Groups({"brief:read"})
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ApiSubresource
     * @Groups({"user:read", "user:write"})
     * @Groups({"promo:read"})
     */
    private $profil;

    /**
     * @ORM\Column(type="boolean",options={"default" : false})
     * @Groups({"groupe:read"})
     * @Groups({"promo:read"})
     */
    private $archivage;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="user")
     */
    private $chats;

    public function __construct()
    {
        $this->chats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getAbbr();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {

        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    public function getFisrtName(): ?string
    {
        return $this->fisrtName;
    }

    public function setFisrtName(string $fisrtName): self
    {
        $this->fisrtName = $fisrtName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getPhoto()
    {
        if($this->photo)
        {
            $data = stream_get_contents($this->photo);
            if(!$this->photo){
                fclose($this->photo);
            }


            return base64_encode($data);
        }else 
        {
            return null;
        }
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of plainPassword
     */ 
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     *
     * @return  self
     */ 
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->contains($chat)) {
            $this->chats->removeElement($chat);
            // set the owning side to null (unless already changed)
            if ($chat->getUser() === $this) {
                $chat->setUser(null);
            }
        }

        return $this;
    }

    public function getInfoUser()
    {
        return ["id"=>$this->getId(),"prenom"=>$this->getFisrtName(),"nom"=>$this->getLastName(),"email"=>$this->getEmail()];
    }
}
