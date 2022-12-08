<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\SearchSponsorAVGType;
use App\Form\SponsorType;
use App\Repository\SponsorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sponsor')]
class SponsorController extends AbstractController
{

    #[Route('/', name: 'app_sponsor_index', methods: ['GET'])]
    public function index(Request $request,SponsorRepository $sponsorRepository,PaginatorInterface $paginator): Response
    {
        $sponsor =$sponsorRepository->findAll();
        $sponsor = $paginator->paginate(
            $sponsor, /* query NOT result */
            $request->query->getInt('page', 1),
            7
        );
        return $this->render('sponsor/index.html.twig', [
            'sponsors' => $sponsor,
        ]);
    }

    #[Route('/new', name: 'app_sponsor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SponsorRepository $sponsorRepository): Response
    {
        $sponsor = new Sponsor();
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sponsorRepository->save($sponsor, true);

            return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsor/new.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{idSponsor}', name: 'app_sponsor_show', methods: ['GET'])]
    public function show(Sponsor $sponsor): Response
    {
        return $this->render('sponsor/show.html.twig', [
            'sponsor' => $sponsor,
        ]);
    }

    #[Route('/{idSponsor}/edit', name: 'app_sponsor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
    {
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sponsorRepository->save($sponsor, true);

            return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsor/edit.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{idSponsor}', name: 'app_sponsor_delete', methods: ['POST'])]
    public function delete(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sponsor->getIdSponsor(), $request->request->get('_token'))) {
            $sponsorRepository->remove($sponsor, true);
        }

        return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
    }

    public function findSponsorByAVG(Request $request, SponsorRepository $sponsorRepository)
    {

        //Show all sponsors
        $sponsors = $sponsorRepository->findAll();
        //Formulaire de recherche
        $searchForm = $this->createForm(SearchSponsorAVGType::class);
        $searchForm->add('Search', SubmitType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            dump($request->request->get('search_sponsor_by_avg')['minMontant']);
            $request->request->get('search_sponsor_by_avg');
            die;
            $minMoy = $searchForm['minMontant']->getData();
            $maxMoy = $searchForm['maxMontant']->getData();
            $resultOfSearch = $repository->findSponsorByAVG($minMontant, $maxMontant);
            return $this->render('sponsor/index.html.twig', [
                'sponsors' => $resultOfSearch,
                'searchSponsor' => $searchForm->createView()]);
        }
        return $this->render('sponsor/index.html.twig', ['sponsors' => $sponsors,
            'searchSponsor' => $searchForm->createView()]);

    }




}
