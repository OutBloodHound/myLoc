<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/category/{id}', name: 'app_categoryShow')]
    public function show(int $id, CategoryRepository  $cr,): Response 
    {
        //aller chercher l'objet de categorie correspondant à l'id
        $category = $cr->find($id);
        
        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }
}
