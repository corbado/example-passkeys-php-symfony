<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixture extends Fixture
{

    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {

        $users = [
            new User(
                "demo_user",
                "demo_user@company.com",
                "demo12"
            ),
            new User(
                "max",
                "max@company.com",
                "maxPW"
            ),
            new User(
                "john",
                "john@company.com",
                "123456"
            )
        ];

        foreach ($users as $item) {
            $manager->persist($item);
        }
        $manager->flush();
    }
}