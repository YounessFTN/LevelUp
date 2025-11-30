<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Entity\Feedback;
use App\Form\ChallengeType;
use App\Form\FeedbackType;
use App\Repository\FeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/challenge')]
class ChallengeController extends AbstractController
{
    #[Route('/new', name: 'app_challenge_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $challenge = new Challenge();
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Debug: afficher les erreurs si le formulaire n'est pas valide
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            
            if ($form->isValid()) {
                $user = $this->getUser();
                
                if (!$user) {
                    throw $this->createAccessDeniedException('Vous devez être connecté');
                }
                
                $challenge->setProposer($user);
                $challenge->setPublicationStatus('en_attente');
                $challenge->setCreationDate(new \DateTime());
                
                try {
                    $em->persist($challenge);
                    $em->flush();
                    
                    $this->addFlash('success', 'Défi proposé ! Il sera publié après modération.');
                    return $this->redirectToRoute('app_home');
                    
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de la création : ' . $e->getMessage());
                }
            }
        }

        return $this->render('challenge/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_challenge_show', requirements: ['id' => '\d+'])]
    public function show(
        Challenge $challenge,
        Request $request,
        EntityManagerInterface $em,
        FeedbackRepository $feedbackRepository
    ): Response {
        // Récupérer les feedbacks liés à ce défi
        $feedbacks = $feedbackRepository->findBy(
            ['challenge' => $challenge],
            ['createdAt' => 'DESC']
        );

        // Formulaire d'ajout de feedback (uniquement pour les connectés)
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);

        // Traiter le formulaire si l'utilisateur est connecté
        if ($this->getUser()) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Lier le feedback au défi et à l'utilisateur
                $feedback->setChallenge($challenge);
                $feedback->setAuthor($this->getUser());

                $em->persist($feedback);
                $em->flush();

                $this->addFlash('success', 'Votre retour a été partagé !');

                // Redirection pour éviter le re-post du formulaire
                return $this->redirectToRoute('app_challenge_show', ['id' => $challenge->getId()]);
            }
        }

        return $this->render('challenge/show.html.twig', [
            'challenge' => $challenge,
            'feedbacks' => $feedbacks,
            'form' => $form,
        ]);
    }
}