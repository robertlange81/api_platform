<?php

namespace App\tests\Functional;

use App\Entity\ItemListing;
use App\Entity\User;
use App\Test\CustomApiTestCase;

class ItemListingResourceTest extends CustomApiTestCase {

    function testCreateItemListing()
    {
        $client = self::createClient();
        $client->request('POST', '/api/items', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => []
        ]);

        $this->createUserAndLogIn($client, 'itemplease@example.com', '123');
        $this->assertResponseStatusCodeSame(204);
    
        $cheesyData = [
            'title' => 'Mystery item... kinda green',
            'description' => 'Item without owner set',
            'price' => 5000
        ];
    
        $client->request('POST', '/api/items', [
            'json' => $cheesyData,
        ]);
        $this->assertResponseStatusCodeSame(201);
    }
    
    function testOnlyUserCanLogIn()
    {
        $client = self::createClient();
        $client->request('POST', '/api/items', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => []
        ]);
        
        $this->logIn($client, 'itemplease@example.com', '123');
        $this->assertResponseStatusCodeSame(401, "owner: This value should not be blank.");
    }
    
    public function testUpdateItemListing()
    {
        $client = self::createClient();
        $user1 = $this->createUser('user1@example.com', 'foo');
        $user2 = $this->createUser('user2@example.com', 'foo');
        $user3 = $this->createUser('admin@example.com', 'foo', ['ROLE_ADMIN']);
        $itemListing = new ItemListing('Block of cheddar');
        $itemListing->setOwner($user1);
        $itemListing->setPrice(1000);
        $itemListing->setDescription('mmmm');
        $itemListing->setIsPublished(true);
    
        $em = $this->getEntityManager();
        $em->persist($itemListing);
        $em->flush();
    
        $this->logIn($client, 'user1@example.com', 'foo');
        $client->request('PUT', '/api/items/'.$itemListing->getId(), [
            'json' => ['title' => 'updated']
        ]);
        $this->assertResponseStatusCodeSame(200);
    
        $this->logIn($client, 'user2@example.com', 'foo');
        $client->request('PUT', '/api/items/'.$itemListing->getId(), [
            'json' => ['title' => 'updated']
        ]);
        $this->assertResponseStatusCodeSame(403, 'only author can updated');
    
        /* this should not be possible: change owner of item by request from other user */
        /* security and access_control work identically, except that security runs before the object is updated from the posted data. */
        $this->logIn($client, 'user2@example.com', 'foo');
        $client->request('PUT', '/api/items/'.$itemListing->getId(), [
            'json' => ['title' => 'updated', 'owner' => '/api/users/' . $user2->getId()]
        ]);
        $this->assertResponseStatusCodeSame(403);
    
        /* ... BUT Admin User should be able to steal items */
        $this->logIn($client, 'admin@example.com', 'foo');
        $client->request('PUT', '/api/items/'.$itemListing->getId(), [
            'json' => ['title' => 'updated', 'owner' => '/api/users/' . $user3->getId()]
        ]);
        $this->assertResponseStatusCodeSame(200);
    }
    
    /*
     * @expectedException PHPUnit_Framework_Error_Notice
     */
    public function testGetItemListingCollection()
    {
        $client = self::createClient();
        $user = $this->createUser('itemplese@example.com', 'foo');
    
        $itemListing1 = new ItemListing('item1');
        $itemListing1->setOwner($user);
        $itemListing1->setPrice(1000);
        $itemListing1->setDescription('item');
        $itemListing2 = new ItemListing('item2');
        $itemListing2->setOwner($user);
        $itemListing2->setPrice(1000);
        $itemListing2->setDescription('item');
        $itemListing2->setIsPublished(true);
        $itemListing3 = new ItemListing('item3');
        $itemListing3->setOwner($user);
        $itemListing3->setPrice(1000);
        $itemListing3->setDescription('item');
        $itemListing3->setIsPublished(true);
        $em = $this->getEntityManager();
        $em->persist($itemListing1);
        $em->persist($itemListing2);
        $em->persist($itemListing3);
        $em->flush();
    
        $client->request('GET', '/api/items');
        $data = $client->getResponse()->toArray();
        $this->assertEquals($data['hydra:totalItems'], 2); // only published
    }
    
    public function testGetItemListingItem()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'itemplease@example.com', 'foo');
        $itemListing1 = new ItemListing('item1');
        $itemListing1->setOwner($user);
        $itemListing1->setPrice(1000);
        $itemListing1->setDescription('item');
        $itemListing1->setIsPublished(false);
        $em = $this->getEntityManager();
        $em->persist($itemListing1);
        $em->flush();
    
        $client->request('GET', '/api/items/'.$itemListing1->getId());
        $this->assertResponseStatusCodeSame(404);
    
        // refresh the user & elevate
        $user = $em->getRepository(User::class)->find($user->getId());
        $user->setRoles(['ROLE_ADMIN']);
        $em->flush();
    
        // can fetch as admin
        $this->logIn($client, 'itemplease@example.com', 'foo');
        $client->request('GET', '/api/items/'.$itemListing1->getId());
        $this->assertResponseStatusCodeSame(200);
    
        $client->request('GET', '/api/users/'.$user->getId());
        $data = $client->getResponse()->toArray();
        $this->assertEmpty($data['itemListings']);
    }
}

