<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;

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
        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class, [
                    'label' => 'Prénom'
            ])
            ->add('lastName', TextType::class,[
            'label' => 'Nom'
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image de profil',
            ])
            ->getForm();

        // TODO https://symfony.com/doc/current/controller/upload_file.html

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
