<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Firstname', TextType::class, [
                'label' => 'Prenom',
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('Lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue',
                'constraints' => [
                    new Assert\NotBlank()
                ]

            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new Assert\Length([
                        'min' => 5,
                        'max' => 10,
                        'minMessage' => 'Le code postal doit comporter au moins 5 caractères.',
                        'maxMessage' => 'Le code postal ne peut pas dépasser 10 caractères.',
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
