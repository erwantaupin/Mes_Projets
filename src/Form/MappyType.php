<?php

namespace App\Form;

use App\Entity\MapPp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MappyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'nom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'le nom de votre point d\'interet'
                ]
            ])
            ->add('lat', TextType::class, [
                'label' => 'lattitude',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ex: 54.6543'
                ]
            ])
            ->add('lon', TextType::class, [
                'label' => 'longitude',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ex: 54.6543'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MapPp::class,
        ]);
    }
}
