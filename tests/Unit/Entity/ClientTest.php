<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testClientCreation(): void
    {
        $client = new Client();
        $client->setName('Laura Lazzaro');
        $client->setEmail('laura@example.com');
        $client->setPhone('0612345678');
        $client->setAddress('123 Rue de la Paix, Paris');

        $this->assertEquals('Laura Lazzaro', $client->getName());
        $this->assertEquals('laura@example.com', $client->getEmail());
        $this->assertEquals('0612345678', $client->getPhone());
        $this->assertEquals('123 Rue de la Paix, Paris', $client->getAddress());
    }

    public function testClientNullableFields(): void
    {
        $client = new Client();
        $client->setName('Laura Lazzaro');
        $client->setEmail('laura@example.com');

        $this->assertNull($client->getPhone());
        $this->assertNull($client->getAddress());
        $this->assertNull($client->getUpdatedAt());
    }

    public function testClientPrePersist(): void
    {
        $client = new Client();
        $client->setCreatedAtValue();

        $this->assertInstanceOf(\DateTimeImmutable::class, $client->getCreatedAt());
    }
}