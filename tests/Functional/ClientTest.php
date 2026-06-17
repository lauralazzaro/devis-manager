<?php

namespace App\Tests\Functional;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientTest extends WebTestCase
{
    private function createAuthenticatedClient(): \Symfony\Bundle\FrameworkBundle\KernelBrowser
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $user = $userRepository->findOneBy([]);

        $client->loginUser($user);

        return $client;
    }

    public function testClientIndexIsAccessible(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/en/client');

        $this->assertResponseIsSuccessful();
    }

    public function testClientNewPageIsAccessible(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/en/client/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testCreateClient(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/en/client/new');

        $client->submitForm('Save', [
            'client[name]' => 'Test Client',
            'client[email]' => 'test@example.com',
            'client[phone]' => '0612345678',
            'client[address]' => '123 Rue de Test',
        ]);

        $this->assertResponseRedirects();
    }
}
