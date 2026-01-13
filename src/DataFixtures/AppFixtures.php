<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $password = $this->hasher->hashPassword($user, 'admin');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail('user '.$i.'@example.com');
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);
            $manager->persist($user);

            $users[] = $user;
        }

        for ($i = 0; $i < 300; $i++) {
            shuffle($users);
            foreach ($users as $user) {
                $blog = new Blog($user)
                    ->setTitle('Title '.$i)
                    ->setDescription('Description '.$i)
                    ->setText('Text '.$i);

                $manager->persist($blog);
            }
        }

        $manager->flush();
    }
}
