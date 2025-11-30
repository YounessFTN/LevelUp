<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    // ✅ Constantes pour les références
    public const ADMIN_USER = 'admin-user';
    public const ADMIN2_USER = 'admin2-user';
    public const NORMAL_USER = 'normal-user';

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('youness.fatine1@gmail.com');
        $admin->setUsername('youness');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'youness'));
        
        $manager->persist($admin);
        $this->addReference(self::ADMIN_USER, $admin); // ✅ Référence

        $admin2 = new User();
        $admin2->setEmail('zakariya.belkassem@next-u.fr');
        $admin2->setUsername('zakariya');
        $admin2->setRoles(['ROLE_ADMIN']);
        $admin2->setPassword($this->passwordHasher->hashPassword($admin2, 'belkassem'));
        
        $manager->persist($admin2);
        $this->addReference(self::ADMIN2_USER, $admin2); // ✅ Référence

        $user = new User();
        $user->setEmail('frederic@gmail.com');
        $user->setUsername('Frédéric');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'fredo'));
        
        $manager->persist($user);
        $this->addReference(self::NORMAL_USER, $user); // ✅ Référence

        $manager->flush();
    }
}