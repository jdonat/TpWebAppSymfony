<?php

namespace App\Entity;


use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserAccount implements PasswordAuthenticatedUserInterface
{
    protected int $id;
    protected string $firstname;
    protected string $lastname;
    protected string $email;
    protected string $password;
    protected array $roles;

    public function setId($id) : self
    {
        $this->id = $id;
        return $this;
    }
    public function getId() : int
    {
        return $this->id;
    }
    public function setFirstname($firstname) : self
    {
        $this->firstname = $firstname;
        return $this;
    }
    public function getFirstname() : string
    {
        return $this->firstname;
    }
    public function setLastname($lastname) : self
    {
        $this->lastname = $lastname;
        return $this;
    }
    public function getLastname() : string
    {
        return $this->lastname;
    }
    public function getEmail() : string
    {
        return $this->email;
    }
    public function setEmail($email) : self
    {
        $this->email = $email;
        return $this;
    }
    public function getPassword() : string
    {
        return $this->password;
    }
    public function setPassword($password) : self
    {
        $this->password = $password;
        return $this;
    }
    public function getRoles() : array
    {
        return $this->roles;
    }
    public function setRoles($roles) : self
    {
        $this->roles = $roles;
        return $this;
    }

}