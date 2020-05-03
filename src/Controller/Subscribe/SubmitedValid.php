<?php
namespace App\Controller\Subscribe;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubmitedValid extends AbstractController{

    const LASTNAME = 3;
    const FIRSTNAME = 3;
    const USERNAME = 5;
    const PASSWORD = 8;

    private $lastname;
    private $firsname;
    private $username;
    private $email;
    private $password;
    private $confirmed;

    public function __construct($lastname = null,
                                $firstname = null,
                                $username = null,
                                $email = null,
                                $password = null,
                                $confirmed = null)
    {
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->confirmed = $confirmed;
    }

    public function getErrors(){
        $errors = [];
        if(strlen($this->lastname) <= self::LASTNAME){
            $errors['lastname'] = "Votre nom est trop court";
        }
        if(strlen($this->firstname) <= self::FIRSTNAME){
            $errors['firstname'] = "Votre prenom est trop court";
        }
        if(strlen($this->username) <= self::USERNAME){
            $errors['username'] = "Votre pseudo est trop court";
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = "Votre email est incorrecte";
        }
        if(strlen($this->password) <= self::PASSWORD){
            $errors['password'] = "Votre mot de passe est trop court";
        }
        if($this->password !== $this->confirmed){
            $errors['confirmed'] =  "Vos mot de passe ne correspondent pas";
        }
        return $errors;
    }

    public function isValid(){
        return empty($this->getErrors());
    }
}