<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('user/index.html.twig', [
            'items' => $user->getItems(),
            'loans' => $user->getLoans(), // les prêts sur tes biens
        ]);
        
    }

    // #[Route('/user/{id}/edit', name:'app_user')]
    // public function edit(Id $id): Response
    // {
    //     $user = getUser($id);

    // }

    #[Route('/user/edit/{id}', name:'app_edit_user')]
    public function edit(int $id, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em, UserRepository $ur): Response
    {
        $user = $ur->find($id);

        if($user !== $this->getUser($id)){

            return $this->redirectToRoute('app_home');
        }else{
            $form = $this->createForm(EditUserFormType::class, $user);
            $form = $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {

                $plainPassword = $form->get('password')->getData();

                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('app_user');
            }
            
            return $this->render('user/editUser.html.twig', [
                'EditUserFormType' => $form,
                'user' => $user
            ]);
        }
    }
}
