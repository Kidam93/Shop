<?php 
namespace App\Controller\Subscribe;

use Symfony\Component\HttpFoundation\RequestStack;

class SubscribeRequest{

    protected $requestStack;

    public function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }

    public function getLastname(){
        return $this->requestStack->getCurrentRequest()->request->get('lastname');
    }

    public function getFirstname(){
        return $this->requestStack->getCurrentRequest()->request->get('firstname');
    }

    public function getUsername(){
        return $this->requestStack->getCurrentRequest()->request->get('username');
    }

    public function getEmail(){
        return $this->requestStack->getCurrentRequest()->request->get('email');
    }

    public function getPassword(){
        return $this->requestStack->getCurrentRequest()->request->get('password');
    }

    public function getConfirmed(){
        return $this->requestStack->getCurrentRequest()->request->get('confirmed_password');
    }

}