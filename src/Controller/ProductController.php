<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * FindAll Products
     */
    #[Route('/products', name: 'products.index', methods: ['GET'])]
    public function index(ProductRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $products = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('products/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * Form POST CreateProduct
     */
    #[Route('/products/new', 'products.new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form-> isValid()) {
            $product = $form->getData();
            $this->addFlash(
                'success',
                'Votre produit a été créé avec succès'
            );
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('products.index');
        }

        return $this->render('products/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * UPDATE a product with a form
     */
    #[Route('/products/edit/{id}', 'products.edit', methods: ['GET', 'POST'])]
    public function edit(ProductRepository $repository, int $id, Request $request, EntityManagerInterface $manager) : Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $product = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form-> isValid()) {
            $product = $form->getData();
            $this->addFlash(
                'success',
                'Votre produit a été modifié avec succès'
            );
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('products.index');
        }

        return $this->render('products/edit.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    #[Route('/products/delete/{id}', 'products.delete', methods: ['GET'])]
    public function delete(int $id, ProductRepository $repository, EntityManagerInterface $manager) : Response{
        
        $product = $repository->findOneBy(["id" => $id]);
        $manager ->remove($product);
        $this->addFlash(
            'success',
            'Votre produit a été supprimé avec succès'
        );
        $manager ->flush();

        return $this->redirectToRoute('products.index');
    }
}
