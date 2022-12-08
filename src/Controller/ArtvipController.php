<?php

namespace App\Controller;

use App\Entity\Artvip;
use App\Form\ArtvipType;
use App\Form\ArtvipUpdateTpe;
use App\Form\rechercheType;
use App\Repository\ArtvipRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class ArtvipController extends AbstractController
{

    #[Route('/artvip', name: 'app_artvip')]
    public function index(): Response
    {
        return $this->render('artvip/index.html.twig', [
            'controller_name' => 'ArtvipController',
        ]);
    }

    #[Route('/readvip', name: 'read_article')]
    public function readArt(ManagerRegistry $doctrine):Response
    {$liste= $doctrine->getRepository(Artvip::class)
        ->findAll();
        return $this->render('artvip/index.html.twig',['list1'=>$liste]) ;
    }

    #[Route('/add', name: 'add_article')]
    public function addArt (ManagerRegistry $doctrine,Request $Request, SluggerInterface $slugger):Response
    {//$entitymanager = $doctrine->getManager();
        $obj= new Artvip();
        $form = $this->createForm(ArtvipType::class,$obj);

        $form->handleRequest($Request);
        if(($form->isSubmitted())&&($form->isValid()))
        {

            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $obj->setImage($newFilename);
            }


            $em=$doctrine->getManager();
            $em->persist($obj);

            $em->flush();
            return $this->redirectToRoute('read_article');
        }
        else{
            return $this->render('artvip/add.html.twig',['f'=>$form->createView()]);
        }
    }

    #[Route('/supp/{artID}', name: 'supp_article')]
    public function supprimer($artID,ManagerRegistry $doctrine):Response
    {$obj=$doctrine->getRepository(Artvip::class)->find($artID);
        $em=$doctrine->getManager();
        $em->remove($obj);
        $em->flush();
        return $this->redirectToRoute('read_article');
    }

    #[Route('/update/{artID}',name:'update')]
    public function update($artID ,\Doctrine\Persistence\ManagerRegistry $doctrine,Request $REQUEST): Response
    { $obj=$doctrine
        ->getRepository(Artvip::class)
        ->find($artID);
        $form =$this->createForm(ArtvipUpdateTpe::class,$obj);
        $form->handleRequest($REQUEST);
        if(($form->isSubmitted())&&( $form->isValid())){
            $em=$doctrine->getManager();
            $em->flush(); //mise a jour de table w update
            return $this->redirectToRoute('read_article');}
        else{
            return $this->renderForm('artvip/update.html.twig',['f'=>$form]);
        }
    }

    #[Route('/recherche',name:'rechercher')]
    public function rechercher(ArtvipRepository $artvipRepository, Request $REQUEST){
        $listeR= $artvipRepository->findAll();
        $form =$this->createForm(rechercheType::class);
        $form ->add('recherche', SubmitType:: class);
        $form->handleRequest($REQUEST);
        if(($form->isSubmitted())){

            $artnom=$form['artnom']->getData();

            $listeR= $artvipRepository->recherchename($artnom);

            return $this->renderForm('artvip/search.html.twig',['liste'=>$listeR,'f'=>$form]);}
        else{
            return $this->renderForm('artvip/search.html.twig',['liste'=>$listeR,'f'=>$form]);
        }
    }
}
