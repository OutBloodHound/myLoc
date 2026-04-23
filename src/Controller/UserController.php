<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Item;
use App\Entity\Loan;
use App\Controller\ItemController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
