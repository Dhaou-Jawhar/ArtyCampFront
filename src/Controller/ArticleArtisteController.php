<?php

namespace App\Controller;

use App\Entity\ArticleArtiste;
use App\Form\ArticleType;
use App\Repository\ArticleArtisteRepository;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\Mime\Email;
use PHPMailer\PHPMailer\SMTP;
use Symfony\Component\Mailer\MailerInterface;

class ArticleArtisteController extends AbstractController
{
    #[Route('/portfolio', name: 'app_home1', methods: 'GET')]
    public function index(ArticleArtisteRepository $articleArtisteRepository): Response
    {
        $arts = $articleArtisteRepository->findBy([], ['createdAt' => 'DESC']);  //$arts va représenter le tab de tout les articles
        return $this->render('pages/home.html.twig', compact('arts')); //compact: pour passer notre variables à notre template
    }

    #[Route('/articles/{idArticle<[0-9]+>}', name: 'app_articles_show', methods: 'GET')]
    public function show(ArticleArtiste $article): Response
    {
        return $this->render('pages/show.html.twig', compact('article'));
    }


    // On utilise les méthodes http(GET|POST) dans la méthode create, GET:pour afficher le formulaire
    //POST:pour traitter la formulaire
    #[Route('/articles/create', name: 'app_articles_create', methods: 'GET|POST')]
    public function create(Request $request, EntityManagerInterface $em,UserRepository $userRepo): Response
    {
        $article= new ArticleArtiste;
        $form = $this->createForm(ArticleType::class,$article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hazem=$userRepo->findOneBy(['mail'=>'hazem@live.com']);
            $article->setUser($hazem);
            $em->persist($article);
            $em->flush();
            $this->addFlash('success','Article créé avec succès!');

            return $this->redirectToRoute('app_home1');
        }

        return $this->render('pages/create.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @route("/recherche",name="recherche" ,methods={"GET","POST"})
     *
     *
     */
    public function recherche(Request $req, EntityManagerInterface $entityManager)
    {
        $data = $req->get('searche');
        $repository = $entityManager->getRepository(ArticleArtiste::class);
        $Produitpm = $repository->findBy(['nomA' => $data]);

        return $this->render('pages/home.html.twig', [
            'arts' => $Produitpm
        ]);
    }


    #[Route('/articles/{idArticle<[0-9]+>}/modifier', name: 'app_articles_modif',methods: 'GET|POST')]
    public function modifier(Request $request, EntityManagerInterface $em, ArticleArtiste $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            $this->addFlash('success','Article modifié avec succès!');

            return $this->redirectToRoute('app_home1');

        }

        return $this->render('pages/modifier.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    #[Route('/articles/{idArticle<[0-9]+>}', name: 'app_articles_supp', methods: 'POST|DELETE')]
    public function supprimer(Request $request, EntityManagerInterface $em, ArticleArtiste $article): Response
    {
        if ($this->isCsrfTokenValid('article_suppression_'. $article->getIdArticle(), $request->request->get('csrf_token'))) {
            $em->remove($article);
            $em->flush();
            $this->addFlash('info','Article supprimé avec succès!');

        }
        return $this->redirectToRoute('app_home1');
    }

}