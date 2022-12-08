<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/accEv', name: 'app_club')]
    public function front(EventRepository $eventRepository): Response
    {
        return $this->render('event/affEv.html.twig', [
            'controller_name' => 'EventController',
            'events' => $eventRepository->findAll(),

        ]);
    }

    #[Route('/calender', name: 'app_event_calender')]
    public function calendrier(EventRepository $eventRepository): Response
    {
        $calendars =$eventRepository ->findAll();
        $tab = [];
        foreach($calendars as $calendar){
            $tab[] = [
                'id' => $calendar->getId(),
                'nomev' => $calendar->getNomev(),
                'description' => $calendar->getDescription(),
                'datedeb' => $calendar->getDatedeb(),
                'datefin' => $calendar->getDatefin(),
            ];
        }
        $data = json_encode($tab);
        return $this->render('event/calender.html.twig', compact('data'));
    }


    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(Request $request,EventRepository $eventRepository, PaginatorInterface $paginator): Response
    {
        $event =$eventRepository->findAll();
        $event = $paginator->paginate(
            $event, /* query NOT result */
            $request->query->getInt('page', 1),
            7
        );
        return $this->render('event/index.html.twig', [
            'events' =>$event ,
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);
            $eventRepository->sendsms();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
