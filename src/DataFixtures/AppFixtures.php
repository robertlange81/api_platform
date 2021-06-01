<?php

namespace App\DataFixtures;

use App\DataPersister\UserDataPersister;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @var UserDataPersister
     */
    private $customPersister;
    
    public function __construct(UserDataPersister $customPersister)
    {
        $this->customPersister = $customPersister;
    }
    
    public function load(ObjectManager $manager)
    {
        $password = "123";
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setUsername($i);
            $user->setEmail($i . "@example.com");
            $user->setPlainPassword($password);
            $this->customPersister->persist($user);
        }
    
        $user = new User();
        $user->setUsername("admin");
        $user->setEmail("admin@example.com");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPlainPassword($password);
        $this->customPersister->persist($user);
    }
}
