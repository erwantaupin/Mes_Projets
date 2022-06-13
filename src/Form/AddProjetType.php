<?php

namespace App\Form;

use App\Entity\ProjetPp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du projet',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre Titre'
                ]
            ])
            ->add('lien_projet', TextType::class, [
                'label' => 'Lien du projet',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'HTTPS://...'
                ]
            ])
            ->add('main_image', FileType::class, [
                'label' => 'Image du projet',
                'multiple' => false,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('lien_github', TextType::class, [
                'label' => 'Lien Github du projet',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'HTTPS://...'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjetPp::class,
        ]);
    }
}
