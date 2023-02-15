<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $users = [
            new User(
                "demo_user",
                "demo_user@company.com",
                password_hash("demo12", PASSWORD_DEFAULT)
            ),
            new User(
                "max",
                "max@company.com",
                password_hash("maxPW", PASSWORD_DEFAULT)
            ),
            new User(
                "john",
                "john@company.com",
                password_hash("123456", PASSWORD_DEFAULT)
            )
        ];

        foreach ($users as $item) {
            $manager->persist($item);
        }
        $manager->flush();
    }
}
