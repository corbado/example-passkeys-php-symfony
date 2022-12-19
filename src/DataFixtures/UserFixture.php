<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Tests\Common\DataFixtures\TestEntity\User;
use Faker\Factory;

class UserFixture extends Fixture
{

    private $faker;

    private $users;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->users = [
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
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->$users as $item) {
            $manager->persist($item);
        }
        $manager->flush();
    }

/*    public function load(ObjectManager $manager): void
{
// $product = new Product();
// $manager->persist($product);
$manager->flush();
}
*/
}