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
        //$this->denyAccessUnlessGranted('ROLE_USER');
        // Get calendar event and search request
        $value = $request->get('search');
        $events = $calendar->findAll();

        // Get calendar events
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
                'allDay' => $event->getAllDay(),
            ];
        }

        $data = json_encode($calendarEvents);

        return $this->render('dashboards/index.html.twig', [
            'contacts' => $contactRepository->findLatestContact($value),
            'current_navlink' => 'dashboard',
            'data' => $data
        ]);
    }

}
