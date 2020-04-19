<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class baseController extends AbstractController{
    
    /**
     * @Route("", name="")
     */
    public function home(){

        return $this->render("pages/home.html.twig");
    }
}