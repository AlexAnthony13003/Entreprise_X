<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Customer;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('customerId', EntityType::class, [
            'class' => Customer::class,
            'label' => 'Client',
            'choice_label' => function (Customer $customer) {
                return $customer->getLastName() . ' ' . $customer->getFirstName();
            },
            'placeholder' => 'Choisissez un client',
        ])
        // ->add('idCat', EntityType::class, [
        //     'class' => Category::class,
        //     'label' => 'Categorie',
        //     'choice_label' => 'NameCat', // Propriété à afficher dans la liste déroulante
        //     'placeholder' => 'Sélectionnez une catégorie',
        //     'mapped' => false, // Ne pas mapper ce champ avec l'entité "Invoice"
        //     'required' => false, // Le champ n'est pas obligatoire
        // ])
        
        // ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            
        //     $form = $event->getForm();
        //     $data = $event->getData();

        //     var_dump($data); // Vérifions les données reçues

        //     // Vérifiez si le champ "Catégorie" est sélectionné
        //     if (isset($data['idCat'])) {
        //         $categoryId = $data['idCat'];

        //         var_dump($categoryId); // Vérifions l'ID de la catégorie sélectionnée

        //         // Mettez à jour les options du champ "Produits" en fonction de la catégorie sélectionnée
        //         $form->add('products', EntityType::class, [
        //             'class' => Product::class,
        //             'choice_label' => 'Name',
        //             'query_builder' => function (ProductRepository $productRepository) use ($categoryId) {
        //                 return $productRepository->createQueryBuilder('p')
        //                     ->where('p.idCat = :categoryId')
        //                     ->setParameter('categoryId', $categoryId);
        //             },
        //             'multiple' => true,
        //             'expanded' => true,
        //             'label' => 'Produits'
        //         ]);
        //     }
        // })
        ->add('products', EntityType::class, [
            'label' => 'Produits',
            'class' => Product::class,
            'query_builder' => function (ProductRepository $product) {
                return $product->createQueryBuilder('p')
                    ->orderBy('p.Name', 'ASC');
            },
            'choice_label' => 'name',
            'multiple' => true, // Permet de sélectionner plusieurs produits
            'expanded' => true, // Affiche les produits sous forme de cases à cocher
        ])
        ->add('date', DateType::class, [
            'label' => 'Date', 
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Valider'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
