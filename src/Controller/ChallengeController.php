<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChallengeController extends AbstractController
{
    #[Route('/challenge', name: 'app_challenge')]
    public function index(): Response
    {
        return $this->render('challenge/index.html.twig', [
            'controller_name' => 'ChallengeController',
        ]);
    }
}
