<?php

namespace App\tests\Functional;

use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;

class UserResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;
    
    public function testCreateUser()
    {
        $client = self::createClient();
        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'testplease@example.com',
                'username' => 'testplease',
                'password' => 'brie'
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
    
        $this->logIn($client, 'testplease@example.com', 'brie');
    }
    
    public function testGetUser()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'testplease@example.com', 'foo');
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
    
        // TODO new login still needed in new api version?
        $this->logIn($client, 'testplease@example.com', 'foo');
    
        $client->request('GET', '/api/users/'.$user->getId());
        $data = $client->getResponse()->toArray();
        $this->assertArrayHasKey('phoneNumber', $data);
        
        $client->request('GET', '/logout'.$user->getId());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
