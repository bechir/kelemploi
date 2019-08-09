<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    public function loadUsers(ObjectManager $manager)
    {
        // $userData = [$username, $phoneNumber, $password, $email, $roles];
        foreach ($this->getUserData() as [$username, $phoneNumber, $password, $email, $roles]) {
            $user = new User();
            $user->setUsername($username);
            $user->setPhoneNumber($phoneNumber);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setEnabled(true);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$username, $phoneNumber, $password, $email, $roles];
            // ['Administrateur', '+222 26 74 93 34', '12345678', 'contactwebmaster21@gmail.com', ['ROLE_SUPER_ADMIN']],
        ];
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}
