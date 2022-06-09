<?php

namespace App\Controller;

use App\Entity\UserPp;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="security")
     */
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new UserPp();

        $inscriptionForm = $this->createForm(InscriptionType::class, $user);
        $inscriptionForm->handleRequest($request);

        if ($inscriptionForm->isSubmitted() && $inscriptionForm->isValid()) {

            $plaintextPassword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $user->setIsVerified(0);
            $user->setActif(0);
            $manager->persist($user);
            $manager->flush();
        }

        return $this->render('security/index.html.twig', [
            'inscriptionForm' => $inscriptionForm->createView(),
        ]);
    }
    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
    }
}
