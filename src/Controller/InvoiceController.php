<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Product;
use App\Entity\Customer;
use App\Form\InvoiceType;
use App\Form\OrderItemType;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceController extends AbstractController
{
    #[Route('/invoices', name: 'invoices.index', methods: ['GET'])]
    public function index(InvoiceRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $invoices = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('invoices/index.html.twig', [
            'invoices' => $invoices
        ]);
    }
    #[Route('/invoices/new', name: 'invoices.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $invoice = new Invoice();

        $form = $this->createForm(InvoiceType::class, $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Process the data and calculate the total price for the invoice
            $totalPrice = 0;
            $selectedProducts = $invoice->getProducts();
            foreach ($selectedProducts as $product) {
                $price = $product->getPrice();
                $totalPrice += $price;
            }

            // Set the total price for the invoice
            $invoice->setTotalPrice($totalPrice);

            // Save the invoice to the database
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('invoices.index');
        }

        return $this->render('invoices/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/invoices/edit/{id}', 'invoices.edit', methods: ['GET', 'POST'])]
    public function edit(InvoiceRepository $repository, int $id, Request $request, EntityManagerInterface $manager) : Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $invoice = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Process the data and calculate the total price for the invoice
            $totalPrice = 0;
            $selectedProducts = $invoice->getProducts();
            foreach ($selectedProducts as $product) {
                $price = $product->getPrice();
                $totalPrice += $price;
            }

            // Set the total price for the invoice
            $invoice->setTotalPrice($totalPrice);

            // Save the invoice to the database
            $manager->persist($invoice);
            $manager->flush();

            return $this->redirectToRoute('invoices.index');
            ;
        }

        return $this->render('invoices/edit.html.twig', [
            'form' =>$form->createView()
        ]);
    }
    #[Route('/invoices/delete/{id}', 'invoices.delete', methods: ['GET'])]
    public function delete(int $id, InvoiceRepository $repository, EntityManagerInterface $manager) : Response{
        
        $invoice = $repository->findOneBy(["id" => $id]);
        $manager ->remove($invoice);
        $manager ->flush();

        return $this->redirectToRoute('invoices.index');
    }
}