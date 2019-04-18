<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testMustObtainTenStudentsAndThreeInformationsByStudent()
    {
        $crawler = $this->client->request('GET', '/students');

        $this->assertEquals(10, $crawler->filter('tr')->count());
        $this->assertEquals(30, $crawler->filter('td')->count());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
