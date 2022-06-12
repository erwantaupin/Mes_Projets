<?php

namespace App\Form;

use App\Entity\UserPp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'pseudo',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'pseudo'
                ]
            ])
            ->add('mail', TextType::class, [
                'label' => 'mail',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'mail'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'password',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'XXXXXXX'
                ]
            ])
            ->add('confirm_password', PasswordType::class, [
                'label' => 'confirme password',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'XXXXXXX'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserPp::class,
        ]);
    }
}
