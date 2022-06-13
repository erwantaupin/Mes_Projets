<?php

namespace App\Controller;

use App\Entity\ProjetPp;
use App\Form\AddProjetType;
use App\Service\ProjetForm;
use App\Repository\UserPpRepository;
use App\Repository\ProjetPpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EntityManagerInterface $manager, ProjetPpRepository $projetPpRepository, ProjetForm $neoform): Response
    {
        // affichage des projet
        $projet = $projetPpRepository->findAll();

        // parti new projet
        $user = $this->getUser();
        $newprojet = new ProjetPp();
        $newprojet->setRelation($user);

        $projetForm = $this->createForm(AddProjetType::class, $newprojet);
        $projetForm->handleRequest($request);

        if ($projetForm->isSubmitted()) {
            return $neoform->handleProjetForm($projetForm);
        }

        return $this->render('home/index.html.twig', [
            'projetForm' => $projetForm->createView(),
            'contenu' => $projet,
            'archive' => 'archive',
        ]);
    }

    /**
     * @route("/user/test", name="test")
     */
    public function test(): Response
    {


        return $this->render('home/test.html.twig', []);
    }

    /**
     * @Route("/user/traitement/{id}/{action}", name="traitement_archive_project")
     */
    public function traitement_archive_project(ProjetPpRepository $projet, EntityManagerInterface $manager, $id, $action): Response
    {
        $user = $this->getUser();

        $reponse = $projet->find($id);
        $iduser = $reponse->getRelation();

        if ($user == $iduser) {
            if (isset($action)) {
                if ($action === 'archive') {

                    $reponse->setArchive(1);

                    $manager->persist($reponse);
                    $manager->flush();

                    return $this->redirectToRoute('home');
                }

                return $this->render('home/index.html.twig', [
                    'contenu' => $reponse,
                    'archive' => 'archive',
                ]);
            }
        } else {
            $this->addFlash("error", "l'action demandé vous à été refusé, car vous n'ête pas le proprietaire de ce contenu!");
            return $this->redirectToRoute('home');
        }
    }
}
