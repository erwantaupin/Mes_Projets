<?php

namespace App\Controller;

use App\Entity\ProjetPp;
use App\Form\AddProjetType;
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
    public function index(Request $request, EntityManagerInterface $manager, ProjetPpRepository $projetPpRepository): Response
    {
        // affichage des projet
        $projet = $projetPpRepository->findAll();

        // parti new projet
        $user = $this->getUser();
        $newprojet = new ProjetPp();
        $newprojet->setRelation($user);

        $projetForm = $this->createForm(AddProjetType::class, $newprojet);
        $projetForm->handleRequest($request);

        if ($projetForm->isSubmitted() && $projetForm->isValid()) {

            $images = $projetForm->get('main_image')->getData();

            if ($images != null) {
                // on genere un new nom de fichier
                $fichier = md5(uniqid()) . '.' . $images->guessExtension();
                // on copie le fichier dans le dossier uploads
                $images->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                // on stocke l'image dans la BDD (son nom)

                $newprojet->setMainImage($fichier);
                $newprojet->setArchive(0);

                $manager->persist($newprojet);
                $manager->flush();
                $this->addFlash("success", "le projet à été mis en ligne !");
                return $this->redirectToRoute('home');
            } else {
                $this->addFlash("error", "l'image inseré fait plus de 2mo et/ou aucune image n'a été inseré!");
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('home/index.html.twig', [
            'projetForm' => $projetForm->createView(),
            'contenu' => $projet,
            'archive' => 'archive',
        ]);
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
