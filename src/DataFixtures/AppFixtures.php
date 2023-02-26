<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Contact;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for($i = 1; $i <= 100; $i++){
            $contact = new Contact();
            $contact->setName($faker->name);
            $contact->setEmail($faker->freeEmail);
            $contact->setPhone($faker->phoneNumber);
            $contact->setWebsite($faker->safeEmailDomain);
            $contact->setAddress($faker->address);
            $contact->setCreatedAt(new \DateTimeImmutable('now'));
            $contact->setUpdatedAt(new \DateTimeImmutable('now'));
            $manager->persist($contact);
        }

        $user = new User();
        $user->setEmail("admin@devel.com");
        $user->setRoles(array("ROLE_ADMIN"));
        $user->setPassword('$2y$13$fB9Wp/9rWScxzpBZxSCWxeX0fgk32PC9tQSx56Qtd0OVDkQQVmv6C');
        $user->setIsBanned(0);
        $user->setIsActive(1);
        $user->setIsConfirm(1);
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setUpdatedAt(new \DateTimeImmutable('now'));
        $manager->persist($user);

         for($i = 1; $i <= 99; $i++){
            $user = new User();
            $user->setEmail($faker->safeEmail);
            $user->setRoles(array("ROLE_USER"));
            $user->setPassword('$2y$13$fB9Wp/9rWScxzpBZxSCWxeX0fgk32PC9tQSx56Qtd0OVDkQQVmv6C');
            $user->setIsBanned(0);
            $user->setIsActive(1);
            $user->setIsConfirm(1);
            $user->setCreatedAt(new \DateTimeImmutable('now'));
            $user->setUpdatedAt(new \DateTimeImmutable('now'));
            $manager->persist($user);
        }


        $manager->flush();
    }
}
