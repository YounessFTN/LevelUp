<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Entity\User;
use App\Repository\ChallengeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin_dashboard')]
    public function dashboard(
        UserRepository $userRepository,
        ChallengeRepository $challengeRepository,
        EntityManagerInterface $em
    ): Response {
        $totalUsers = $userRepository->count([]);
        
        // Utiliser une requête SQL native avec les bons noms de table/colonne
        $conn = $em->getConnection();
        $sql = 'SELECT COUNT(*) FROM "user" WHERE roles::text LIKE :role';
        try {
            $adminUsers = $conn->executeQuery($sql, ['role' => '%"ROLE_ADMIN"%'])->fetchOne();
        } catch (\Exception $e) {
            // Si ça échoue, fallback sur filtrage PHP
            $allUsers = $userRepository->findAll();
            $adminUsers = count(array_filter($allUsers, fn(User $u) => in_array('ROLE_ADMIN', $u->getRoles())));
        }
        
        $recentUsers = $userRepository->findBy([], ['registrationDate' => 'DESC'], 5);
        
        // ✅ Statistiques des défis
        $totalChallenges = $challengeRepository->count([]);
        $pendingChallenges = $challengeRepository->count(['publicationStatus' => 'en_attente']);
        $publishedChallenges = $challengeRepository->count(['publicationStatus' => 'publié']);

        return $this->render('admin/dashboard.html.twig', [
            'total_users' => $totalUsers,
            'admin_users' => $adminUsers,
            'recent_users' => $recentUsers,
            'total_challenges' => $totalChallenges,
            'pending_challenges' => $pendingChallenges,
            'published_challenges' => $publishedChallenges,
        ]);
    }

    // ========== GESTION DES UTILISATEURS (code existant) ==========
    
    #[Route('/users', name: 'app_admin_users')]
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}/promote', name: 'app_admin_user_promote', methods: ['POST'])]
    public function promoteUser(User $user, EntityManagerInterface $em): Response
    {
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            $user->setRoles(['ROLE_ADMIN']);
            $em->flush();
            $this->addFlash('success', "L'utilisateur {$user->getUsername()} est maintenant administrateur.");
        }

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/users/{id}/demote', name: 'app_admin_user_demote', methods: ['POST'])]
    public function demoteUser(User $user, EntityManagerInterface $em): Response
    {
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $user->setRoles(['ROLE_USER']);
            $em->flush();
            $this->addFlash('success', "L'utilisateur {$user->getUsername()} n'est plus administrateur.");
        }

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/users/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em, Request $request): Response
    {
        // Protection : ne pas supprimer l'utilisateur connecté
        if ($user === $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            return $this->redirectToRoute('app_admin_users');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', "L'utilisateur {$user->getUsername()} a été supprimé.");
        }

        return $this->redirectToRoute('app_admin_users');
    }

    // ========== GESTION DES DÉFIS (nouveau) ==========
    
    #[Route('/challenges', name: 'app_admin_challenges')]
    public function listChallenges(ChallengeRepository $challengeRepository): Response
    {
        // Récupère uniquement les défis en attente de validation
        $pendingChallenges = $challengeRepository->findBy(
            ['publicationStatus' => 'en_attente'],
            ['creationDate' => 'DESC']
        );

        return $this->render('admin/challenges.html.twig', [
            'challenges' => $pendingChallenges,
        ]);
    }

    #[Route('/challenges/{id}/publish', name: 'app_admin_challenge_publish', methods: ['POST'])]
    public function publishChallenge(Challenge $challenge, EntityManagerInterface $em): Response
    {
        // Change le statut à "publié"
        $challenge->setPublicationStatus('publié');
        $em->flush();

        $this->addFlash('success', "Le défi \"{$challenge->getTitle()}\" a été publié.");

        return $this->redirectToRoute('app_admin_challenges');
    }

    #[Route('/challenges/{id}/delete', name: 'app_admin_challenge_delete', methods: ['POST'])]
    public function deleteChallenge(Challenge $challenge, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$challenge->getId(), $request->request->get('_token'))) {
            $title = $challenge->getTitle();
            $em->remove($challenge);
            $em->flush();

            $this->addFlash('success', "Le défi \"{$title}\" a été supprimé.");
        }

        return $this->redirectToRoute('app_admin_challenges');
    }
}