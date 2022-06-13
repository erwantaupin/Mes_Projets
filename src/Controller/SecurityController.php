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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        return $this->render('security/login.html.twig', []);
    }
    /**
     * @Route("/subscription", name="subscription")
     */
    public function subscription(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
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
            $user->setRoles(['ROLE_USER']);
            $user->setActif(0);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Vous êtes à présent inscrit ! Veuillez verifié vos email afin de finalisé votre inscription.');
            return $this->redirectToRoute('login');
        }

        return $this->render('security/subscription.html.twig', [
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
