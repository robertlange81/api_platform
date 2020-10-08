<?php

namespace App\tests\Functional;

use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;
    
    public function testCreateUser()
    {
        $client = self::createClient();
        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'cheeseplease@example.com',
                'username' => 'cheeseplease',
                'password' => 'brie'
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
    
        $this->logIn($client, 'cheeseplease@example.com', 'brie');
    }
    
    public function testGetUser()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'cheeseplease@example.com', 'foo');
        $user->setPhoneNumber('555.123.4567');
        $em = $this->getEntityManager();
        $em->flush();
    
        $client->request('GET', '/api/users/'.$user->getId());
    
        $data = $client->getResponse()->toArray();
        $this->assertArrayNotHasKey('phoneNumber', $data);
    
        // refresh the user & elevate
        $user = $em->getRepository(User::class)->find($user->getId());
        $user->setRoles(['ROLE_ADMIN']);
        $em->flush();
    
        // TODO new login still needed in new api verision?
        $this->logIn($client, 'cheeseplease@example.com', 'foo');
    
        $client->request('GET', '/api/users/'.$user->getId());
        $data = $client->getResponse()->toArray();
        $this->assertArrayHasKey('phoneNumber', $data);
    }
}
