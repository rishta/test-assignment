<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use ApiPlatform\Core\Bridge\Symfony\Routing\Router;
use App\Entity\PhoneBookEntry;
use App\Entity\PhoneBookEntryShare;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransport;
use Symfony\Contracts\Service\ServiceProviderInterface;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

class PhoneBookEntryShareTest extends AbstractApiTestCase
{
    private Client $client;
    private Router $router;

    protected function setup(): void
    {
        $this->client = $this->createClientWithCredentials();
        $router = static::$container->get('api_platform.router');
        if (!$router instanceof Router) {
            throw new \RuntimeException('api_platform.router service not found.');
        }
        $this->router = $router;
    }

    public function testGetCollection(): void
    {
        $response = $this->client->request('GET', '/phone-book-entry-shares');
        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@context' => '/contexts/PhoneBookEntryShare',
            '@id' => '/phone-book-entry-shares',
            '@type' => 'hydra:Collection',
        ]);

        // self::assertCount(30, $response->toArray()['hydra:member']);

        //static::assertMatchesJsonSchema(file_get_contents(__DIR__.'/schemas/phone-book-entry-share.json'));
        // self::assertMatchesResourceCollectionJsonSchema(PhoneBookEntryShare::class);
    }


    public function testCreateEntry()
    {
        $response = $this->client->request('POST', '/phone-book-entries', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'name' => 'test entry',
                'number' => '+48666069420',
            ]]);
        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            "@context" => "/contexts/PhoneBookEntry",
            "@type" => "PhoneBookEntry",
            "name" => "test entry",
            "number" => "+48666069420",
        ]);
        $json = $response->toArray();
        self::assertTrue(Uuid::isValid($json['uuid']));
        // self::assertTrue(is_numeric($json['id']));
        self::assertNotContains('id', array_keys($json));
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        $number = $phoneNumberUtil->parse($json['number'], null);
        static::$container
            ->get('doctrine')
            ->getRepository(PhoneBookEntry::class)
            ->findOneBy([
                'name' => $json['uuid'],
                'number' => $number,
                // 'owner' => str_replace('/users/', '', $json['owner'])
            ]);
    }
}
