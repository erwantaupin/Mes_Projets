<?php

namespace App\Service;

use Twig\Environment;
use App\Entity\ProjetPp;
use App\Repository\ProjetPpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProjetForm

{
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameters, Environment $environment)
    {
        $this->em = $em;
        $this->parameters = $parameters;
        $this->environment = $environment;
    }

    public function handleProjetForm(FormInterface $projetForm): JsonResponse
    {
        if ($projetForm->isValid()) {
            return $this->handleValidProjetForm($projetForm);
        } else {
            return $this->handleInvalidProjetForm($projetForm);
        }
    }

    public function handleValidProjetForm(FormInterface $projetForm): JsonResponse
    {

        $projet = $projetForm->getdata();

        $upload = $projetForm['image']->getdata();

        $newFileName = $this->renameUploadFile($upload, UploadedFile::class);

        $projet->setMainImage($newFileName);
        $projet->setArchive(0);


        $this->em->persist($projet);
        $this->em->flush();

        return new JsonResponse([
            'code' => ProjetPp::PROJET_ADDED_SUCCESSFULLY,
            'html' => $this->environment->render('_partials/_card.html.twig', [
                'contenu' => $projet,
                'archive' => 'archive'
            ])
        ]);
    }

    public function handleInvalidProjetForm(FormInterface $projetForm): JsonResponse
    {
        return new JsonResponse([
            'code' => ProjetPp::PROJET_INVALID_FORM
        ]);
    }
    private function renameUploadFile(UploadedFile $UploadedFile): string
    {
        $newFileName = uniqid() . '.' . $UploadedFile->guessExtension();
        $UploadedFile->move(
            $this->parameters->get('images_directory'),
            $newFileName
        );

        return $newFileName;
    }
}
