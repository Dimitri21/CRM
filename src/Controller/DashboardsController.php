<?php

namespace App\Controller;
use App\Repository\CalendarRepository;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardsController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */

    public function index(Request $request, CalendarRepository $calendar, ContactRepository $contactRepository): Response
    {
        // FULL CALENDAR
        // Get celendar events of user
        $events = $calendar->getUserCalendar();

        // Prepare data
        $calendarEvents = [];
        foreach ($events as $event) {
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

        // Get events where the user is member
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

        // Get search request
        $value = $request->get('request');

        $contacts = $contactRepository->findLatestContact($value);

        // return view
        return $this->render('dashboards/index.html.twig', [
            'contacts' => $contacts,
            'current_navlink' => 'dashboard',
            'data' => $data
        ]);
    }

}
