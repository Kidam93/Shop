<?php 
namespace App\Controller\Login;

use Symfony\Component\HttpFoundation\RequestStack;

class LoginRequest{

    protected $requestStack;

    public function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }

    public function getUsername(){
        return $this->requestStack->getCurrentRequest()->request->get('username');
    }

    public function getPassword(){
        return $this->requestStack->getCurrentRequest()->request->get('password');
    }

}