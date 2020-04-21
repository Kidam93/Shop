<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdminFixtures extends Fixture
{
    const USERNAME = 'demo';
    
    const PASSWORD = 'demo';

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $password = password_hash(self::PASSWORD, PASSWORD_BCRYPT);
        $admin = new Admin();
        $admin->setUsername(self::USERNAME)
                ->setPassword($password);
        $manager->persist($admin);
        $manager->flush();
    }
}
