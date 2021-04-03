<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    /**
     * @Route("/legal", name="legal")
     */
    public function index(): Response
    {
        return $this->render('legal/index.html.twig', [
        ]);
    }

    /**
     * @Route("/confidentiality", name="confidentiality")
     */
    public function confidentiality(): Response
    {
        return $this->render('legal/confidentiality.html.twig', [
        ]);
    }
}
