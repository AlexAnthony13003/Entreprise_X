<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * FindAll categories
     */
    #[Route('/categories', name: 'categories.index', methods: ['GET'])]
    public function index(CategoryRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('Categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Form POST Createcategory
     */
    #[Route('/categories/new', 'categories.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $category = new category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->addFlash(
                'success',
                'Votre categorie a été créé avec succès'
            );
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute('categories.index');
        }

        return $this->render('categories/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * UPDATE a category with a form
     */
    #[Route('/categories/edit/{id}', 'categories.edit', methods: ['GET', 'POST'])]
    public function edit(CategoryRepository $repository, int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $category = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->addFlash(
                'success',
                'Votre categorie a été modifié avec succès'
            );
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute('categories.index');
        }

        return $this->render('Categories/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/categories/delete/{id}', name: 'categories.delete', methods: ['GET'])]
    public function deleteCategory(int $id, CategoryRepository $repository, EntityManagerInterface $manager): Response
    {
        $category = $repository->findOneBy(["id" => $id]);
        $products = $category->getProducts();
    
        if (!$products->isEmpty()) {
            $this->addFlash(
                'danger',
                'Vous devez au préalable supprimer tous les produits de cette catégorie'
            );
            return $this->redirectToRoute('categories.index');
        }
    
        $manager->remove($category);
        $manager->flush();
    
        $this->addFlash(
            'success',
            'La catégorie a été supprimée avec succès'
        );
    
        return $this->redirectToRoute('categories.index');
    }
    
}
