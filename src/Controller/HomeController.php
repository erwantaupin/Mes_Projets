<?php

namespace App\Controller;

use App\Entity\ProjetPp;
use App\Form\AddProjetType;
use App\Service\ProjetForm;
use Spipu\Html2Pdf\Html2Pdf;
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

    /**
     * @Route("/admin/pdf/{id}/{user}", name="traitement_pdf")
     */
    public function traitement_pdf(UserPpRepository $userpp, ProjetPpRepository $projet, $id, $user): Response
    {
        $projettarget = $projet->find($id);
        $usertarget = $userpp->find($user);
        // parti user
        $username = $usertarget->getUsername();
        $mail = $usertarget->getmail();
        $date = $usertarget->getCreatedAt();
        $neodate = $date->format('d-m-Y');
        // parti projet
        $titre = $projettarget->getTitre();
        $lienprojet = $projettarget->getLienProjet();
        $liengithub = $projettarget->getLienGithub();
        $dateprojet = $projettarget->getCreatedAt();
        $neodateprojet = $dateprojet->format('d-m-Y');
        $image = $projettarget->getMainImage();
        // dd($projettarget, $usertarget);

        $htmlpdf = new Html2Pdf('P', 'A4', 'fr');

        $html = "
        <page backtop='7mm' backbottom='7mm' backleft='10mm' backright='10mm'>
            <style type='text/css'>
                .info-user{
                    margin-left: 20px;
                }
                .info-user-right{
                    text-align: right;
                    margin-right: 20px;
                }
                .titre-menu{
                    text-align: center;
                    font-size: 22px;
                }
                .info{
                    font-size: 16px;
                }
                th{
                    border-right: 1px solid black;
                    padding-left: 10px;
                    padding-right: 10px;
                }
                td{
                    border-right: 1px solid black;
                    padding-left: 10px;
                    padding-right: 10px;
                }
                .table{
                    text-align: center;
                }
                .imagesize{
                    margin: 50px;
                }
                .image img{
                    height: 300px;
                }
                .minititre{
                    text-align: center;
                    font-size: 18px;
                }
                .link{
                    text-align: center;
                    font-size: 16px;
                }
                .margin{
                    margin-top: 60px;
                }
                .date{
                    text-align: right;
                }
            </style>
                <div class='titre-menu'>Compte Utilisateur</div>
            <div class='margin'></div>
                <div class='info-user'>
                    <strong class='info'>username:</strong></div>
                    <div class='info-user-right'> $username</div>
            <br>
                <div class='info-user'><strong class='info'>mail:</strong></div>
                <div class='info-user-right'> $mail</div>
            <br>
                <div class='info-user'><strong class='info'>date de creation:</strong></div>
                <div class='info-user-right'> $neodate</div>
            <div class='margin'></div>
            <div class='titre-menu'>$titre</div>
        <div class='margin'></div>
        <div class='minititre'>lien du projet</div>
        <div class='link'>$lienprojet</div>
        <br>
        <div class='minititre'>lien du github</div>
        <div class='link'>$liengithub</div>
        <div class='image'><img class='imagesize' src='./uploads/$image'/></div>
        <div class='date'>date de creation: $neodateprojet</div>
    </page>
        ";

        $htmlpdf->writeHTML($html);
        $htmlpdf->output();

        return $this->render('pdf/pdf.html.twig', []);
    }
}
