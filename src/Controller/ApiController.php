<?php

namespace App\Controller;

use App\Repository\ChallengeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response; // <-- ajouté
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/challenge/random', name: 'api_challenge_random', methods: ['GET'])]
    public function getRandomChallenge(ChallengeRepository $challengeRepository): JsonResponse
    {
        $challenge = $challengeRepository->findRandomChallenge();

        if ($challenge === null) {
            return $this->json([
                'error' => 'Aucun défi disponible pour le moment'
            ], 404);
        }

        $data = [
            'id' => $challenge->getId(),
            'titre' => $challenge->getTitle(),
            'description' => $challenge->getDescription(),
            'date_creation' => $challenge->getCreationDate()->format('d/m/Y'),
            'auteur' => $challenge->getProposer()->getUsername(),
            'nombre_feedbacks' => $challenge->getFeedbacks()->count(),
        ];

        return $this->json($data);
    }

    #[Route('/docs', name: 'api_docs')]
    public function docs(): Response
    {
        return $this->render('api/index.html.twig');
    }
}
