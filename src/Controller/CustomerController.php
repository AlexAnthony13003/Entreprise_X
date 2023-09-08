<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    /**
     * FindAll Customer
     */
    #[Route('/customers', name: 'customers.index', methods: ['GET'])]
    public function index(CustomerRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $customers = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('customers/index.html.twig', [
            'customers' => $customers
        ]);
    }

    /**
     * Form POST Create a customer
     */
    #[Route('/customers/new', 'customers.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $manager->persist($customer);
            $this->addFlash(
                'success',
                'Le client a été créé avec succès'
            );
            $manager->flush();

            return $this->redirectToRoute('customers.index');
        }

        return $this->render('customers/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * UPDATE an customer with a form
     */
    #[Route('/customers/edition/{id}', 'customers.edit', methods: ['GET', 'POST'])]
    public function edit(CustomerRepository $repository, int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $customer = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $this->addFlash(
                'success',
                'Le client a été modifié avec succès'
            );
            $manager->persist($customer);
            $manager->flush();

            return $this->redirectToRoute('customers.index');
        }

        return $this->render('customers/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/customers/suppression/{id}', name: 'customers.delete', methods: ['GET'])]
    public function delete(int $id, CustomerRepository $repository, EntityManagerInterface $manager): Response
    {
        $customer = $repository->findOneBy(["id" => $id]);
        $invoices = $customer->getInvoices();

        if (!$invoices->isEmpty()) {
            $this->addFlash(
                'danger',
                'Vous devez au préalable supprimer toutes les factures en lien avec ce client'
            );
            return $this->redirectToRoute('customers.index');
        }
        $manager->remove($customer);
        $manager->flush();
        $this->addFlash(
            'success',
            'Le client a été supprimé avec succès'
        );

        return $this->redirectToRoute('customers.index');
    }
}
