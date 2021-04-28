<?php

namespace App\tests\Functional;

use App\Test\CustomApiTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PostResourceTest extends CustomApiTestCase
{
    /** @var KernelBrowser */
    protected $client;
    
    public function createDefaultPost(): void
    {
        $this->createUserAndLogIn($this->client, 'postUser@example.com', '123');
        $this->assertResponseStatusCodeSame(204);
        
        $postData = [
            'message' => 'Tolle Nachricht'
        ];
        
        try {
            $this->client->request('POST', '/api/posts', [
                'json' => $postData,
            ]);
        } catch (TransportExceptionInterface $e) {
        }
    }
    
    protected function setUp(): void
    {
        self::bootKernel();
        $this->client = static::createClient();
    }

    public function testCreatePost()
    {
        $this->createDefaultPost();
        $this->assertResponseStatusCodeSame(201);
    }
    
    public function testFailCreatePostNotLoggedIn()
    {
        $this->client = self::createClient();
        $this->client->request('GET', '/logout', [
            'headers' => ['Content-Type' => 'application/json']
        ]);
    
        $postData = [
            'message' => 'Nicht eingeloggt - DuDuDu!'
        ];
    
        try {
            $this->client->request('POST', '/api/posts', [
                'json' => $postData,
            ]);
        } catch (TransportExceptionInterface $e) {
        }
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
    
    public function testFindAllPosts(): void
    {
        $this->createDefaultPost();
        $this->client->request(Request::METHOD_GET, '/api/posts', [
            'headers' => ['accept' => 'application/json']
        ]);
        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $result = json_decode($this->client->getResponse()->getContent(), true);
        var_dump($result);
        $this->assertEquals(1, count($result));
    }
}

