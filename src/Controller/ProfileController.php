<?php

namespace App\Controller;

use App\Repository\ChallengeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/my-space', name: 'app_my_space')]
    #[IsGranted('ROLE_USER')]
    public function index(ChallengeRepository $challengeRepository): Response
    {
        $user = $this->getUser();

        // Récupère les défis créés par l'utilisateur
        $myChallenges = $challengeRepository->findBy(
            ['proposer' => $user],
            ['creationDate' => 'DESC']
        );

        // Récupère les défis où l'utilisateur a participé (via feedbacks)
        $commentedChallenges = $challengeRepository->findChallengesCommentedBy($user);

        return $this->render('profile/index.html.twig', [
            'my_challenges' => $myChallenges,
            'commented_challenges' => $commentedChallenges,
        ]);
    }
}