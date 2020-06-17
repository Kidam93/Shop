<?php
namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\AdminRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdminController extends AbstractController{

    private $articleRepo;

    private $adminRepo;

    private $em;

    protected $requestStack;

    private $session;

    public function __construct(ArticleRepository $articleRepo,
                                AdminRepository $adminRepo,
                                EntityManagerInterface $em,
                                RequestStack $requestStack,
                                SessionInterface $session){
        $this->articleRepo = $articleRepo;
        $this->adminRepo = $adminRepo;
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->session = $session;
    }

    /**
     * @Route("admin/login", name="admin.login")
     */
    public function login(){
        $hash = null;
        $username = $this->requestStack->getCurrentRequest()->request->get('username');
        $password = $this->requestStack->getCurrentRequest()->request->get('password');
        if($this->adminRepo->isPassword($username)){
            $hash = $this->adminRepo->isPassword($username)[0]->getPassword();
        }
        $isTrue = password_verify($password, $hash);
        if($isTrue === true){
            $id = (int)$this->adminRepo->isPassword($username)[0]->getId();
            $this->session->set('id', $id);
            return $this->redirectToRoute("admin.index");
        }
        return $this->render("admin/login.html.twig");
    }

    /**
     * @Route("admin/disconnected", name="admin.disconnected")
     */
    public function disconnected(){
        $this->session->set('id', null);
        return $this->redirectToRoute("admin.login");
    }
    
    /**
     * @Route("/admin", name="admin.index")
     */
    public function index(){
        $articles = $this->articleRepo->findAll();
        if($this->session->get('id') === null){
            return $this->redirectToRoute("admin.login");
        }
        return $this->render("admin/admin.html.twig", [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/show-{id}", name="admin.show")
     */
    public function show($id){
        if($this->session->get('id') === null){
            return $this->redirectToRoute("admin.login");
        }
        $article = $this->articleRepo->find($id);
        return $this->render("admin/show.html.twig", [
            'article' => $article
        ]);
    }

    /**
     * @Route("/update-{id}", name="admin.update")
     */
    public function update($id){
        if($this->session->get('id') === null){
            return $this->redirectToRoute("admin.login");
        }
        $article = $this->articleRepo->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $request = $this->requestStack->getCurrentRequest();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($article);
            $this->em->flush();
            return $this->redirectToRoute("admin.index");
        }
        return $this->render("admin/update.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete-{id}", name="admin.delete")
     */
    public function delete($id){
        if($this->session->get('id') === null){
            return $this->redirectToRoute("admin.login");
        }
        $article = $this->articleRepo->find($id);
        $this->em->remove($article);
        $this->em->flush();
        return $this->redirectToRoute("admin.index");
    }

    /**
     * @Route("/new", name="admin.new")
     */
    public function new(){
        if($this->session->get('id') === null){
            return $this->redirectToRoute("admin.login");
        }
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $request = $this->requestStack->getCurrentRequest();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($article);
            $this->em->flush();
            return $this->redirectToRoute("admin.index");
        }
        return $this->render("admin/new.html.twig", [
            'form' => $form->createView()
        ]);
    }
}