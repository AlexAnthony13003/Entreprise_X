<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\Product;
use App\Entity\Customer;
use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InvoiceType extends AbstractType
{

    
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $products = $this->productRepository->findAll();
        $choices = [];
        foreach ($products as $product) {
            $choices[$product->getName()] = $product->getPrice(); // Modifier ici selon le champ contenant le prix du produit
        }
        $builder
            ->add('customerId', EntityType::class, [
                'class' => Customer::class,
                'label' => 'Client',
                'choice_label' => function (Customer $customer) {
                    return $customer->getLastName() . ' ' . $customer->getFirstName();
                },
                'placeholder' => 'Choisissez un client',
            ])
            ->add('products', EntityType::class, [
                'label' => 'Produits',
                'class' => Product::class,
                'query_builder' => function (ProductRepository $product) {
                    return $product->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => function (Product $product) {
                    return $product->getName() . ' (' . $product->getPrice() . '€)';
                },
                'choices' => $products,
                'multiple' => true, // Permet de sélectionner plusieurs produits
                'expanded' => true, // Affiche les produits sous forme de cases à cocher
                'choice_attr' => function (Product $product) {
                    return ['data-price' => $product->getPrice(), 'class' => 'form-check-input form-control']; // Ajoutez l'attribut data-price avec la valeur du prix du produit
                },
            ])
            ->add('totalPrice', MoneyType::class, [
                'attr' => [
                    'id' => 'total'
                ],
                'label' => 'Prix Total',
                'required' => false, // Le champ n'est pas obligatoire
                
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
