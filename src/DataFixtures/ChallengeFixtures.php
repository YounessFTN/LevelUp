<?php

namespace App\DataFixtures;

use App\Entity\Challenge;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ChallengeFixtures extends Fixture implements DependentFixtureInterface
{
    // ✅ Liste de défis réalistes
    private const CHALLENGES = [
        [
            'title' => 'Sourire à 5 inconnus',
            'description' => 'Aujourd\'hui, faites un sourire sincère à 5 personnes que vous ne connaissez pas. Un simple sourire peut illuminer la journée de quelqu\'un.',
        ],
        [
            'title' => 'Complimenter un collègue',
            'description' => 'Faites un compliment sincère à un collègue de travail ou un camarade de classe. Valorisez leurs efforts ou leurs qualités.',
        ],
        [
            'title' => 'Aider quelqu\'un à porter ses courses',
            'description' => 'Proposez votre aide à une personne qui porte des sacs lourds. Un petit geste peut faire une grande différence.',
        ],
        [
            'title' => 'Laisser votre place dans les transports',
            'description' => 'Dans le bus, le métro ou le tram, cédez votre place à quelqu\'un qui en a besoin.',
        ],
        [
            'title' => 'Appeler un proche que vous n\'avez pas vu depuis longtemps',
            'description' => 'Prenez 10 minutes pour appeler un ami ou un membre de votre famille que vous n\'avez pas contacté récemment.',
        ],
        [
            'title' => 'Offrir un café à un SDF',
            'description' => 'Achetez un café chaud ou un sandwich à une personne dans le besoin.',
        ],
        [
            'title' => 'Ramasser 5 déchets dans la rue',
            'description' => 'Contribuez à rendre votre quartier plus propre en ramassant quelques déchets que vous croisez.',
        ],
        [
            'title' => 'Remercier un commerçant',
            'description' => 'Prenez le temps de remercier chaleureusement un commerçant, un serveur ou un agent d\'entretien pour leur travail.',
        ],
        [
            'title' => 'Tenir la porte à quelqu\'un',
            'description' => 'Un geste simple mais qui montre votre attention aux autres. Tenez la porte à plusieurs personnes aujourd\'hui.',
        ],
        [
            'title' => 'Partager un repas avec quelqu\'un de seul',
            'description' => 'Invitez un collègue, un voisin ou un ami qui est seul à partager un repas avec vous.',
        ],
        [
            'title' => 'Donner des vêtements que vous ne portez plus',
            'description' => 'Triez votre armoire et donnez les vêtements en bon état que vous ne portez plus à une association.',
        ],
        [
            'title' => 'Écrire un message de remerciement',
            'description' => 'Écrivez un message sincère à quelqu\'un qui a eu un impact positif dans votre vie.',
        ],
        [
            'title' => 'Proposer votre aide à un voisin âgé',
            'description' => 'Demandez à un voisin âgé s\'il a besoin d\'aide pour faire ses courses, sortir les poubelles, etc.',
        ],
        [
            'title' => 'Partager un article inspirant sur les réseaux sociaux',
            'description' => 'Partagez du contenu positif et inspirant qui pourrait aider ou motiver d\'autres personnes.',
        ],
        [
            'title' => 'Laisser un pourboire généreux',
            'description' => 'Si vous en avez les moyens, laissez un pourboire plus généreux que d\'habitude à un serveur.',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        // ✅ Récupérer les utilisateurs depuis UserFixtures
        /** @var User $admin */
        $admin = $this->getReference(UserFixtures::ADMIN_USER, User::class);
        /** @var User $admin2 */
        $admin2 = $this->getReference(UserFixtures::ADMIN2_USER, User::class);
        /** @var User $user */
        $user = $this->getReference(UserFixtures::NORMAL_USER, User::class);

        $users = [$admin, $admin2, $user];
        $challengeIndex = 0;

        // ✅ Créer 5 défis pour chaque utilisateur
        foreach ($users as $currentUser) {
            for ($i = 0; $i < 5; $i++) {
                $challengeData = self::CHALLENGES[$challengeIndex % count(self::CHALLENGES)];
                
                $challenge = new Challenge();
                $challenge->setTitle($challengeData['title']);
                $challenge->setDescription($challengeData['description']);
                $challenge->setProposer($currentUser);
                $challenge->setCreationDate(new \DateTime('-' . rand(1, 30) . ' days')); // Date aléatoire dans les 30 derniers jours
                
                // ✅ Alterner entre "publié" et "en_attente"
                if ($challengeIndex % 3 === 0) {
                    $challenge->setPublicationStatus('en_attente');
                } else {
                    $challenge->setPublicationStatus('publié');
                }
                
                $manager->persist($challenge);
                $challengeIndex++;
            }
        }

        $manager->flush();
    }

    // ✅ Cette fixture dépend de UserFixtures
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}