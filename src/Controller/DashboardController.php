<?php

namespace App\Controller;

use App\Repository\UserPpRepository;
use App\Repository\ProjetPpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function dashboard(ProjetPpRepository $ProjetPpRepository): Response
    {
        $contenu = $ProjetPpRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'contenu' => $contenu,
            'archiver' => 'archiver',
            'dearchiver' => 'dearchiver',
        ]);
    }
    /**
     * @Route("/admin/utilisateur", name="utilisateur")
     */
    public function utilisateur(UserPpRepository $UserPpRepository): Response
    {
        $contenu = $UserPpRepository->findAll();

        return $this->render('admin/utilisateur.html.twig', [
            'contenu' => $contenu,
            'bloquer' => 'bloquer',
            'debloquer' => 'debloquer'
        ]);
    }
    /**
     * @Route("/admin/admintraitementuser/{id}/{action}", name="admintraitementuser")
     */
    public function traitement_utilisateur(UserPpRepository $UserPpRepository, EntityManagerInterface $manager, $id, $action): Response
    {
        $reponse = $UserPpRepository->find($id);
        if ($action === 'bloquer') {

            $reponse->setRoles(["ROLE_BANNI"]);
            $reponse->setActif(1);

            $manager->persist($reponse);
            $manager->flush();

            return $this->redirectToRoute('utilisateur');
        }
        if ($action === 'debloquer') {

            $reponse->setRoles(["ROLE_USER"]);
            $reponse->setActif(0);

            $manager->persist($reponse);
            $manager->flush();

            return $this->redirectToRoute('utilisateur');
        }
        return $this->render('admin/traitement.html.twig', [
            'contenu' => $reponse,
            'bloquer' => 'bloquer',
            'debloquer' => 'debloquer',
        ]);
    }
    /**
     * @Route("/admin/archive", name="archive")
     */
    public function archive(ProjetPpRepository $ProjetPpRepository): Response
    {
        $contenu = $ProjetPpRepository->findAll();
        return $this->render('admin/archive.html.twig', [
            'contenu' => $contenu,
            'archiver' => 'archiver',
            'dearchiver' => 'dearchiver',
        ]);
    }
    /**
     * @Route("/admin/admintraitementprojet/{id}/{action}", name="admintraitementprojet")
     */
    public function traitement_projet(ProjetPpRepository $ProjetPpRepository, EntityManagerInterface $manager, $id, $action): Response
    {
        $reponse = $ProjetPpRepository->find($id);

        if ($action === 'archiver') {

            $reponse->setArchive(1);

            $manager->persist($reponse);
            $manager->flush();

            return $this->redirectToRoute('dashboard');
        }
        if ($action === 'dearchiver') {


            $reponse->setArchive(0);

            $manager->persist($reponse);
            $manager->flush();

            return $this->redirectToRoute('archive');
        }
        return $this->render('admin/traitement.html.twig', [
            'contenu' => $reponse,
            'archiver' => 'archiver',
            'dearchiver' => 'dearchiver',
        ]);
    }
}
