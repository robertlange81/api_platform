<?php

namespace App\tests\Functional;

use App\Entity\Post;
use App\Entity\User;
use App\Test\CustomApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PostResourceTest extends CustomApiTestCase
{
    public function testCreatePost()
    {
        $client = self::createClient();

        $this->createUserAndLogIn($client, 'postUser@example.com', '123');
        $this->assertResponseStatusCodeSame(204);
    
        $postData = [
            'message' => 'Tolle Nachricht'
        ];
    
        try {
            $client->request('POST', '/api/posts', [
                'json' => $postData,
            ]);
        } catch (TransportExceptionInterface $e) {
        }
        $this->assertResponseStatusCodeSame(201);
    }
    
    public function testFailCreatePostNotLoggedIn()
    {
        $client = self::createClient();
        $client->request('GET', '/logout', [
            'headers' => ['Content-Type' => 'application/json']
        ]);
    
        $postData = [
            'message' => 'Nicht eingeloggt - DuDuDu!'
        ];
    
        try {
            $client->request('POST', '/api/posts', [
                'json' => $postData,
            ]);
        } catch (TransportExceptionInterface $e) {
        }
        $this->assertResponseStatusCodeSame(401);
    }
}

