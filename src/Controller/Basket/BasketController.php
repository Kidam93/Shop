<?php
namespace App\Controller\Basket;

use App\Entity\Basket;
use App\Repository\UserRepository;
use App\Repository\AdminRepository;
use App\Repository\ArticleRepository;
use App\Repository\BasketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BasketController extends AbstractController{

    private $articleRepo;

    private $adminRepo;

    private $userRepo;

    private $basketRepo;

    private $em;

    protected $requestStack;

    private $session;

    public function __construct(ArticleRepository $articleRepo,
                                AdminRepository $adminRepo,
                                UserRepository $userRepo,
                                BasketRepository $basketRepo,
                                EntityManagerInterface $em,
                                RequestStack $requestStack,
                                SessionInterface $session){
        $this->articleRepo = $articleRepo;
        $this->adminRepo = $adminRepo;
        $this->userRepo = $userRepo;
        $this->basketRepo = $basketRepo;
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->session = $session;
    }

    /**
     * @Route("/article-{id}", name="home.basket")
     */
    public function basket($id){

        $userId = $this->session->get('user_id');
        if($userId === null){
            // dd('Ajout...');
            // $data = [];
            // array_push($data, $id);
            // $_SESSION['panier'] = $data;
            // 
            // return $this->redirectToRoute("home");
            return new Response("panier");
        }else{
            $exist = $this->basketRepo->findJoin($userId, $id);
            if(!empty($exist)){
                $basketId = (int)$exist[0]['basket_id'];
                $this->basketRepo->updateQuantity($basketId);
            }else{
                $basket = new Basket();
                $basket->setQuantity(1);
                $basket->addUser($this->userRepo->find($userId));
                $basket->addArticle($this->articleRepo->find($id));
                $this->em->persist($basket);
                $this->em->flush();
            }
            return $this->redirectToRoute("home");
        }
    }

    /**
     * @Route("/basket", name="basket")
     */
    public function show(){
        if(!empty($this->session->get('user_id'))){
            $userId = $this->session->get('user_id');
            $articles = $this->basketRepo->findBasket($userId);
            $quantity = $this->basketRepo->totalQTE($userId);
            if(!empty($quantity)){
                $datas = [];
                foreach($quantity as $data){
                    $datas[] = (int)$data['quantity'];
                }
                dd($datas);
            }
            return $this->render("home/basket.html.twig", [
                'articles' => $articles
            ]);
        }else{
            // 
            // VIRTUAL BASKET
            // 
            return $this->render("home/basket.html.twig", [
                'articles' => $articles
            ]);
        }
    }

    /**
     * @Route("/basket-delete-{id}", name="basket.delete")
     */
    public function delete($id){
        $userId = $this->session->get('user_id');
        $articleId = $this->basketRepo->selectJoin((int)$id, $userId);
        if(!empty($articleId)){
            $this->basketRepo->deleteBasket((int)$articleId[0]['id']);
        }
        return $this->redirectToRoute("basket");
    }
}