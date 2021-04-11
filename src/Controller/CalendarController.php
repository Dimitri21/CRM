<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\User;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/calendar")
 */

class CalendarController extends AbstractController
{
    /**
     * @Route("/", name="calendar_index", methods={"GET"})
     */
    public function index(CalendarRepository $calendarRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Search for calendar if searchbox value is set

        $value = $request->get('search');


        if (isset($value) and !empty($value)) {
            $calendars = $calendarRepository->findCalendar($value);
        } else {
            $calendars = $calendarRepository->getUserCalendarWithMember();
        }

        $pagination = $paginator->paginate(
            $calendars,
            $request->query->getInt('page', 1),
            8

        );

        return $this->render('calendar/index.html.twig', [
            'calendars' => $pagination,
            'current_navlink' => 'calendar',
        ]);
    }

    /**
     * @Route("/agenda", name="calendar_agenda", methods={"GET"})
     */
    public function agenda(CalendarRepository $calendarRepository): Response
    {

        // Get calendar event
        $events = $calendarRepository->getUserCalendar();

        // Format for json
        $calendarEvents = [];
        foreach ($events as $event) {

            // For each event, get members of the event
            $eventUser = [];
            foreach ($event->getMembers() as $member) {
                $eventUser[] = [
                  'id' => $member->getId(),
                  'firstName' => $member->getFirstName(),
                  'lastName' => $member->getLastName(),
                ];
            }

            $calendarEvents[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
            ];
        }

        // Get event where the user is member
        foreach ($this->getUser()->getMembers() as $eventMember) {
            $calendarEvents[] = [
                'id' => $eventMember->getId(),
                'start' => $eventMember->getStart()->format('Y-m-d H:i:s'),
                'end' => $eventMember->getEnd()->format('Y-m-d H:i:s'),
                'title' => $eventMember->getTitle(),
                'description' => $eventMember->getDescription(),
                'backgroundColor' => $eventMember->getBackgroundColor(),
                'borderColor' => $eventMember->getBorderColor(),
                'textColor' => $eventMember->getTextColor(),
            ];
        }

        $data = json_encode($calendarEvents);

        return $this->render('calendar/agenda.html.twig', [

            'current_navlink' => 'dashboard',
            'current_navlink' => 'calendar',
            'data' => $data
        ]);

    }

    /**
     * @Route("/new", name="calendar_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $calendar = new Calendar();

        // Get user and set calendar to user
        $user = $this->getUser();
        $calendar->setCreatedBy($user);

        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //filter if user is member of his own event
            $members = $form["members"]->getData();
            $calendarEvent = $form->getData();
            foreach ($members as $member) {
                if($user == $member) {
                    $calendarEvent->removeMember($member);
                }
            };

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($calendar);
            $entityManager->flush();

            // Flash message success
            $this->addFlash(
                'success',
                'Votre rendez-vous a été créé'
            );

            return $this->redirectToRoute('calendar_agenda');
        }

        return $this->render('calendar/new.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
            'current_navlink' => 'calendar',
        ]);
    }

    /**
     * @Route("/{id}", name="calendar_show", methods={"GET"})
     */
    public function show(Calendar $calendar, CalendarRepository $calendarRepository): Response
    {
        // Verifiying user has access to calendar
        $user = $this->getUser();
        $members = $calendar->getMembers();
        $memberAccess = false;
        foreach ($members as $member) {
            if ($member == $user) {
                $memberAccess = true;
            }
        }

        if($calendar->getCreatedBy() == $user or $memberAccess) {
            return $this->render('calendar/show.html.twig', [
                'calendar' => $calendar,
                'current_navlink' => 'calendar'
            ]);
        // Redirect user if entered an invalid url
        } else {
            return $this->render('calendar/index.html.twig', [
                'calendars' => $calendarRepository->getUserCalendar(),
                'current_navlink' => 'calendar'
            ]);
        }

    }

    /**
     * @Route("/{id}/edit", name="calendar_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Calendar $calendar): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //filter if user is member of his own event
            $members = $form["members"]->getData();
            $calendarEvent = $form->getData();
            $userCreator = $calendar->getCreatedBy();

            foreach ($members as $member) {
                if($userCreator == $member) {
                    $calendarEvent->removeMember($member);
                }
            };

            // Flash message success
            $this->addFlash(
                'info',
                'Votre rendez-vous a été modifié'
            );

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('calendar_index');
        }

        return $this->render('calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
            'current_navlink' => 'calendar'
        ]);
    }

    /**
     * @Route("/{id}", name="calendar_delete", methods={"POST"})
     */
    public function delete(Request $request, Calendar $calendar): Response
    {
        // Only access for admin user
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        // Flash message success
        $this->addFlash(
            'danger',
            'Votre rendez-vous a été supprimé'
        );

        return $this->redirectToRoute('calendar_index');
    }
}
