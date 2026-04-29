<?php

namespace App\Controller;

use App\Model\SearchData;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search/{query}', name: 'app_search')]
    public function index($query): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        if($form->isSubmitted() && $form->isValid()){
            dd($searchData);
        }

        return $this->render('search\index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
