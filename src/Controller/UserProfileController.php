<?php

namespace App\Controller;

use App\Form\ProfileUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="user_profile")
     */
    public function index(Request $request): Response
    {
        //Get the user
        $user = $this->getUser();

        //Create form
        $form = $this->createForm(ProfileUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('user_profile/edit.html.twig', [
            'form' => $form->createView()

        ]);
    }
}
