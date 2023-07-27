<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\Product;
use App\Entity\Customer;
use Doctrine\DBAL\Types\FloatType;
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
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'class' => Customer::class,
                'label' => 'Client',
                'choice_label' => function (Customer $customer) {
                    return $customer->getLastName() . ' ' . $customer->getFirstName();
                },
                'placeholder' => 'Choisissez un client',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('products', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
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
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('totalPrice', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'total'
                ],
                'label' => 'Prix Total',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false, // Le champ n'est pas obligatoire
                
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-dark mt-4'
                ],
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
