<?php
// tests/AuthenticationTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Ramsey\Uuid\Uuid;

class AuthenticationTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testLogin(): void
    {
        $client = self::createClient();

        $response = $client->request('POST', '/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'usermail11@email.org',
                'password' => 'plainPassword11',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/phone-book-entry-shares');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $response = $client->request('GET', '/phone-book-entry-shares', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
        $json = $response->toArray();
        print_r($json);
    }
}
