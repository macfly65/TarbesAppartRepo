<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passwordVerify;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DocumentsClient", mappedBy="clientUser", orphanRemoval=true)
     */
    private $documentsClients;

    
    public function __construct()
    {
        $this->documentsClients = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername()
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USE
        $roles[] = 'ROLE_USER';

    return array_unique($roles);
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return (string) $this->password;
    }

    public function setPassword($password): self
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
        // $this->plainPassword = null;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPasswordVerify(): ?string
    {
        return $this->passwordVerify;
    }

    public function setPasswordVerify(?string $passwordVerify): self
    {
        $this->passwordVerify = $passwordVerify;

        return $this;
    }

    /**
     * @return Collection|DocumentsClient[]
     */
    public function getDocumentsClients(): Collection
    {
        return $this->documentsClients;
    }

    public function addDocumentsClient(DocumentsClient $documentsClient): self
    {
        if (!$this->documentsClients->contains($documentsClient)) {
            $this->documentsClients[] = $documentsClient;
            $documentsClient->setClientUser($this);
        }

        return $this;
    }

    public function removeDocumentsClient(DocumentsClient $documentsClient): self
    {
        if ($this->documentsClients->contains($documentsClient)) {
            $this->documentsClients->removeElement($documentsClient);
            // set the owning side to null (unless already changed)
            if ($documentsClient->getClientUser() === $this) {
                $documentsClient->setClientUser(null);
            }
        }

        return $this;
    }
}
