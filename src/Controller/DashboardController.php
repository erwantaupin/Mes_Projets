<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/admin/utilisateur", name="utilisateur")
     */
    public function utilisateur(): Response
    {
        return $this->render('admin/utilisateur.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/admin/archive", name="archive")
     */
    public function archive(): Response
    {
        return $this->render('admin/archive.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
