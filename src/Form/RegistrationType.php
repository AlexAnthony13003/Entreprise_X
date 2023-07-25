<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Adresse Mail : ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Prenom : ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom : ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'minlength' => '2',
                        'maxlength' => '50'
                    ],
                    'label' => 'Mot de Passe : ',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'minlength' => '2',
                        'maxlength' => '50'
                    ],
                    'label' => 'Confirmation du mot de passe : ',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                ],
                'invalid_message' => 'les mots de passes ne correspondent pas.'
            ])
            ->add('submit', SubmitType::class, [
                
                'attr' => [
                    'class' => 'btn btn-dark mt-4'
                ],
                'label' => 'Créer mon compte'
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
