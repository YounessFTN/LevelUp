<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un utilisateur admin
        $admin = new User();
        $admin->setEmail('youness.fatine1@gmail.com');
        $admin->setUsername('youness');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'youness'));
        
        $manager->persist($admin);

        // Créer un deuxième admin
        $admin2 = new User();
        $admin2->setEmail('zakariya.belkassem@next-u.fr');
        $admin2->setUsername('zakariya');
        $admin2->setRoles(['ROLE_ADMIN']);
        $admin2->setPassword($this->passwordHasher->hashPassword($admin2, 'belkassem'));
        
        $manager->persist($admin2);

        // Créer un utilisateur normal
        $user = new User();
        $user->setEmail('frederic@gmail.com');
        $user->setUsername('Frédéric');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'fredo'));
        
        $manager->persist($user);

        $manager->flush();
    }
}