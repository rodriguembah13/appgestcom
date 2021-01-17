<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Util\DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setPlainPassword('super_admin');
        $user->setUsername('super_admin');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN']);
        $user->setDateborn(null);
        $user->setCreatedAt(DateTime::getDateTime('now'));
        $user->setUpdatedAt(DateTime::getDateTime('now'));
        $user->setName("Administrator");

        $manager->persist($user);
        $manager->flush();
    }
}
