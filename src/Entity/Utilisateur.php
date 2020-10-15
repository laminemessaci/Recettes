<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(
 *     fields={"username"},
 *     message="Ce login existe deja!"
 * )
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length (min="5", max="10", minMessage="Il faut plus 5 caractères", maxMessage="Il faut moin de 10 caractères")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length (min="5", max="10", minMessage="Il faut plus 5 caractères", maxMessage="Il faut moin de 10 caractères")
     */
    private $password;

    /**
     * @Assert\Length (min="5", max="10", minMessage="Il faut plus 5 caractères", maxMessage="Il faut moin de 10 caractères")
     * @Assert\EqualTo(propertyPath="password", message="les password ne sont pas equivalent!")
     */
    private $verificationPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVerificationPassword(): ?string
    {
        return $this->verificationPassword;
    }

    /**
     * @param mixed $verificationPassword
     */
    public function setVerificationPassword($verificationPassword)
    {
        $this->verificationPassword = $verificationPassword;
    }


    public function getRoles()
    {
        return [$this->roles];
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function setRoles(?string $roles): self
    {
        if ($roles === null){
            $this->roles = "ROLE_USER";
        }
        return $this;
    }
}
