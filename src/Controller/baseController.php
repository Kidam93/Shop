<?php
namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class baseController extends AbstractController{

    private $articleRepo;

    public function __construct(ArticleRepository $articleRepo){
        $this->articleRepo = $articleRepo;
    }
    
    /**
     * @Route("", name="home")
     */
    public function home(){
        $articles = $this->articleRepo->findAll();
        // dd($articles);
        return $this->render("pages/home.html.twig", [
            'articles' => $articles
        ]);
    }
}