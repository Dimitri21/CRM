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
            isset($donnees->start) && !empty($donnees->start)
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
        $user = $this->getUser();
        //Get the data
        $value = $request->get('request');
        // Get the search response
        $allContacts = $contactRepository->findContact($value);

        $contacts = [];
        foreach ($allContacts as $contact) {
            $contacts[] = [
                'firstName' => $contact->getFirstName(),
                'lastName' => $contact->getLastName(),
                'email' => $contact->getEmail(),
                'phone' => $contact->getPhone(),
                'company'=> $contact->getCompany(),
                'showPath' => $this->generateUrl('contact_show',['id'=>$contact->getId()]),
                'editPath' => $this->isGranted('ROLE_ADMIN')? $this->generateUrl('contact_edit',['id'=>$contact->getId()]):''
            ];
        }

        $contactsJSON = json_encode($contacts);

        //Send data to page
        if(isset($contactsJSON) && !empty($contactsJSON)){
            return new Response($contactsJSON, 200);
        } else {
            // Incomplete data
            return new Response('Incomplete data', 404);
        }

    }
}