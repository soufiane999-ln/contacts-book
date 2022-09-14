<?php

namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactControllerTest extends WebTestCase
{
   private $client;
   private  $url;
   private static $token;
    // TODO create a helper trait that generates randomly these values
    private $data = [
       "addedAt" => "2022-09-10 19:53:34",
       "firstName" => "Jack",
       "lastName" => "Taureau",
       "street" => "Kaiseralle 39D",
       "zip" => 76139,
       "city" => "Berlin",
       "country" => "Germany",
       "phoneNumber" => "+06778273293",
       "birthday" => "1901-09-01",
       "email" => "email@mail.com",
       "picture" => "4RiDRXhpZgAATU0AKgA"
   ];


    public function testLogin(): void
    {
        $route = $this->url . '/login_check';
        $data = ['username' => 'email_0@email.com', 'password' => 'password'];
        $this->client = $this->createClient();
        $this->client->request(
            method: 'POST',
            uri: $route,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($data));
        self::$token = json_decode($this->client->getResponse()->getContent(), true)['token'] ?? '';
        $this->assertResponseIsSuccessful();
    }

    public function testAddingContact(): void
    {
        $route = $this->url . '/customers/1/contacts';
        $response = $this->createClient()->request(
            method: 'POST',
            uri: $route,
            server: ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . self::$token],
            content: json_encode($this->data));
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testGettingContact() {
        $route = $this->url . '/customers/1/contacts/1';
        $response = $this->createClient()->request(
            method: 'GET',
            uri: $route,
            server: ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . self::$token]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testSearchingContactsByName() {
        $route = $this->url . '/customers/1/contacts/jack';
        $response = $this->createClient()->request(
            method: 'GET',
            uri: $route,
            server: ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . self::$token]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testUpdatingContact() {
        $route = $this->url . '/customers/1/contacts/1';
        $this->data['firstName'] = 'Samy';
        $this->data['id'] = 1;
        $this->createClient()->request(
            method: 'PUT',
            uri: $route,
            server: ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . self::$token],
            content: json_encode($this->data));
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDeletingContact() {
        $route = $this->url . '/customers/1/contacts/1';
        $this->createClient()->request(
            method: 'DELETE',
            uri: $route,
            server: ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . self::$token]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
