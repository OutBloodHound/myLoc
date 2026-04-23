<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\Item;
use App\Form\LoanFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LoanController extends AbstractController
{
    // #[Route('/loan', name: 'app_loan')]
    // public function new(): Response
    // {
    //     return $this->render('loan/index.html.twig', [
    //         'controller_name' => 'LoanController',
    //     ]);
    // }

    #[Route('/loan/{id}', name: 'app_loan')]
    public function index(Item $item, Request $request, EntityManagerInterface $em): Response{

    $loan = new Loan();

    $form = $this->createForm(LoanFormType::class, $loan);
    $form-> handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){
        $loan->setUser($this->getUser());
        $loan->setItem($item);

        $em->persist($loan);
        $em->flush();

        return $this->redirectToRoute('app_item_show', ['id' => $item->getId()]);
    }

    return $this->render('loan/index.html.twig', [
        'form' => $form
    ]);
    }
}