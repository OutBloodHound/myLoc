<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ItemController extends AbstractController
{
    #[Route('/item', name: 'app_item_index')]
    public function index(ItemRepository $ir): Response
    {
        $items = $ir->findAll();
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
            'items' => $items
        ]);
    }

    #[Route('/item/{id}', name: 'app_item_show')]
    public function show(int $id, ItemRepository $ir): Response
    {
        $item = $ir->find($id);
        return $this->render('item/show.html.twig', [
            'controller_name' => 'ItemController',
            'item' => $item
        ]);
    }
}
