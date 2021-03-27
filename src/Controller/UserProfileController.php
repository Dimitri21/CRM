<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        //Verifiying if the user exist
        $this->denyAccessUnlessGranted('ROLE_USER');

        //Get the user
        $user = $this->getUser();

        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('picture', FileType::class)
            ->getForm();

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
