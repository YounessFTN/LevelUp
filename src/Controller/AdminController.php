<?php

namespace App\Controller;

use App\Entity\User;
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
    public function dashboard(UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $totalUsers = $userRepository->count([]);
        
        // Utiliser une requête SQL native avec les bons noms de table/colonne
        $conn = $em->getConnection();
        // Utilisez "user" avec guillemets pour PostgreSQL ou essayez le nom exact de votre table
        $sql = 'SELECT COUNT(*) FROM "user" WHERE roles::text LIKE :role';
        try {
            $adminUsers = $conn->executeQuery($sql, ['role' => '%"ROLE_ADMIN"%'])->fetchOne();
        } catch (\Exception $e) {
            // Si ça échoue, fallback sur filtrage PHP
            $allUsers = $userRepository->findAll();
            $adminUsers = count(array_filter($allUsers, fn(User $u) => in_array('ROLE_ADMIN', $u->getRoles())));
        }
        
        $recentUsers = $userRepository->findBy([], ['registrationDate' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'total_users' => $totalUsers,
            'admin_users' => $adminUsers,
            'recent_users' => $recentUsers,
        ]);
    }

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
}