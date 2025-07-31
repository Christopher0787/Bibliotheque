<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User;

        $user
            ->setEmail('admin@bibliotheque.com')
            ->setFirstName('Christopher')
            ->setLastName('Martin')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'Test1234',
                )
            );

        $manager->persist($user);

        for ($i = 0; $i < 10; $i++) {
            $user = new User;

            $user
                ->setEmail(
                    $this->faker->unique()->email()
                )
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setPassword(
                    $this->passwordHasher->hashPassword(
                        $user,
                        'Test1234',
                    )
                );

            $manager->persist($user);
        }

        $manager->flush();
    }
}
