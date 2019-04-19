<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function testMustObtainAnErrorWhenDeleteStudentButIsntExist()
    {
        $this->client->catchExceptions(false);
        $this->expectException(NotFoundHttpException::class);

        $this->client->request('GET', '/students/99999999/edit');
    }

    public function testMustObtainAnErrorWhenEditStudentButIsntExist()
    {
        $this->client->catchExceptions(false);
        $this->expectException(NotFoundHttpException::class);

        $this->client->request('GET', '/students/99999999/edit');
    }

    public function testMustObtainSuccessWhenCreateAStudentWithAllInformations()
    {
        $this->client->request('GET', '/students/add');
        $this->client->submitForm('Add student' , [
            'student[firstName]' => 'test_firstname',
            'student[lastName]' => 'test_lastname'
        ]);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());
    }

    public function testMustObtainErrorsWhenCreateAStudentButAllRequiredInformationsArentProvided()
    {
        $this->client->request('GET', '/students/add');
        $crawler = $this->client->submitForm('Add student' , [
            'student[firstName]' => 'test'
        ]);

        $this->assertEquals(1, $crawler->filter('.form-error-icon')->count());
    }

    public function testMustObtainTenStudentsAndThreeInformationsByStudent()
    {
        $crawler = $this->client->request('GET', '/students');

        $this->assertGreaterThanOrEqual(10, $crawler->filter('tr')->count());
        $this->assertGreaterThanOrEqual(30, $crawler->filter('td')->count());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
