<?php


namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_USER for *every* controller method in this class.
 *
 * @IsGranted("ROLE_USER")
 */

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function majEvent(?Calendar $calendar, Request $request)
    {
        // Get the data
        $donnees = json_decode($request->getContent());

        if (
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor)
        ) {

            // Data are complete, code initiation
            $code = 200;

            // Verifiying id
            if (!$calendar) {
                $calendar = new Calendar;

                // Change code for created
                $code = 201;
            }

            // Object hydratation
            $calendar->setTitle($donnees->title);
            $calendar->setDescription($donnees->description);
            $calendar->setStart(new DateTime($donnees->start));
            $calendar->setEnd(new DateTime($donnees->end));
            $calendar->setBackgroundColor($donnees->backgroundColor);

            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();


            return new Response('Ok', $code);
        } else {
            // Incomplete data
            return new Response('Incomplete data', 404);
        }
    }

    /**
     * @Route("/api/get", name="api_get_contact", methods={"GET"})
     */
    public function getContact(?Contact $contact, Request $request, ContactRepository $contactRepository)
    {
        //Get the data
        $value = $request->get('search');

        $data = $contactRepository->findLatestContact($value);

        if(isset($value) && !empty($value)){
            return new Response($data, 200);
        } else {
            // Incomplete data
            return new Response('Incomplete data', 404);
        }

    }
}