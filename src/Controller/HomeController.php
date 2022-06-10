<?php

namespace App\Controller;

use App\Entity\ProjetPp;
use App\Form\AddProjetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
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
            }
            $newprojet->setArchive(0);

            $manager->persist($newprojet);
            $manager->flush();

            $this->addFlash("success", "le projet à été mis en ligne !");
            return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig', [
            'projetForm' => $projetForm->createView(),
        ]);
    }
}
