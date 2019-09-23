<?php
namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class ApiUser implements UserInterface
{
    
    private $username;
    private $roles;
    private $access_token;
    private $password;
    private $name;
    
    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function __construct($username='',$password='',$access_token='', $roles='', $name='')
    {
        $this->username = $username;
        $this->roles = $roles;
        $this->access_token = $access_token;
        $this->password  = $password;
        $this->name  = $name;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function getSalt()
    {}

    public function getRoles()
    {
        return $this->roles;
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getAccessToken(){
        return $this->access_token;
    }
    

}

