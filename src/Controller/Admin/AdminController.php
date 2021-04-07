<?php

namespace App\Controller\Admin;

use App\Entity\Calendar;
use App\Entity\CategoryContact;
use App\Entity\Contact;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class AdminController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // return parent::index();

        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(CalendarCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CRM');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Calendrier', 'fa fa-calendar-alt',  Calendar::class);
        yield MenuItem::linkToCrud('Contact', 'fa fa-id-card',  Contact::class);
        yield MenuItem::linkToCrud('Utilisateur', 'fa fa-user',  User::class);
        yield MenuItem::linkToCrud('Catégorie contact', 'fa fa-table',  CategoryContact::class);
        yield MenuItem::linkToRoute('Dashboard', 'fas fa-columns',  "dashboard");
        yield MenuItem::linkToLogout('Déconnexion', 'fas fa-sign-out-alt');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
