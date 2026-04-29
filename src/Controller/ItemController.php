<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Entity\Item;
use App\Form\AddItemType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    #[Route('/add', name: 'app_item_add')]
    public function add(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $item = new Item();
        $item->setOwner($this->getUser());

        $form = $this->createForm(AddItemType::class, $item);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('picture')->getData();
            if ($picture) {
                $brochureFileName = $fileUploader->upload($picture);
                $item->setPicture($brochureFileName);
            }

            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('app_user');
        }
        return $this->render('item/add.html.twig', [
            'AddItemType' => $form,
        ]);
    }

    
    #[Route('item/edit/{id}', name: 'app_item_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em, ItemRepository $ir,  FileUploader $fileUploader): Response
    {
        $item = $ir->find($id);
        if(!$item){
            throw $this->createNotFoundException('Item non trouvé');
        }

        if ($item->getOwner() !== $this->getUser()){
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cet item');
        }

        $form = $this->createForm(AddItemType::class, $item);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $picture*/
            $picture = $form->get('picture')->getData();
            if ($picture) {
                $brochureFileName = $fileUploader->upload($picture);
                $item->setPicture($brochureFileName);
            }

            $em->flush();

            return $this->redirectToRoute('app_item_show', ['id' => $item->getId()]);
        }
        return $this->render('item/edit.html.twig', [
            'AddItemType' => $form,
            'item' =>$item
        ]);
    }
}
