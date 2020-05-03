<?php
namespace App\Controller\Subscribe;

use DateTime;
use App\Entity\User;
use Doctrine\DBAL\Driver\SQLSrv\LastInsertId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscribeBDD extends AbstractController{

    const REDIRECT_URL ="http://127.0.0.1:8000/user";

    private $lastname;

    private $firstname;

    private $username;

    private $email;

    private $password;

    public function __construct(string $lastname = null, 
                                string $firstname = null, 
                                string $username = null, 
                                $email = null, 
                                $password = null){
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function hashToken(){
        $length = 60;
        $alphabet = "azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }

    public function insert($em){
        $data = [];
        $data['token'] = $this->hashToken();
        $user = new User();
        $user->setlastname($this->lastname)
            ->setFirstname($this->firstname)
            ->setUsername($this->username)
            ->setEmail($this->email)
            ->setPassword($this->passwordHash())
            ->setToken($data['token'])
            ->setCreatedAt(new DateTime());
        $em->persist($user);
        $em->flush();
        // last Id
        $data['id'] = $user->getId();
        return $data;
    }

    public function sendMailToken($data){
        $subject = 'Validation inscription';
        $message = 'Afin de valider votre compte veuiller cliquer sur ce lien :'
                    . self::REDIRECT_URL . 
                    '/id='
                    . $data['id'] . 
                    '/token='
                    . $data ['token'];
        $headers = 'From: webmaster@example.com' . "\r\n" .
        'Reply-To: webmaster@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        return mail($this->email, $subject, $message, $headers);
    }

    private function passwordHash(){
        return password_hash($this->password, PASSWORD_BCRYPT);
    }
}