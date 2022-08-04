<?php
namespace App\Tests\Controller;
use App\Repository\TaskTodoRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    public function testTodoPage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function createClientTask($client, $crawler){
        $repository = self::getContainer()->get(TaskTodoRepository::class);
        $this->assertResponseIsSuccessful();//ok
        $form = $crawler->filter('html button')->form();
        $form['task'] = 'Trial';
        $client->submit($form);
    }

    public function testCreate()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '//create');
        $this->createClientTask($client,$crawler);
        $this->assertGreaterThan(0, $crawler->filter('html a.tit:contains("")')->count());
        //$this->assertEquals(0, $crawler->filter('html a.tit:contains("Trial")')->count());
    }

    public function testDelete(){
        $client = static::createClient();
        $crawler = $client->request('GET', '//delete');
        $this->assertResponseIsSuccessful(); //ok
        $link = $crawler->filter('html a.close:contains("X")')->eq(0)->link();
        $client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('html a.tit:contains("")')->count());
    }

    public function testChangeStatus(){
        $client = static::createClient();
        $crawler1 = $client->request('POST', '//create');
        $this->createClientTask($client,$crawler1);
        $crawler = $client->request('GET', '//change-status' );
        $this->assertResponseIsSuccessful();
        $link = $crawler->filter('html a.tit:contains("Trial")')->eq(0)->link();
        $client->click($link);
        $this->assertGreaterThan(0,$crawler->filter('html li.tit_after:contains("")')->count());
        //$this->assertEquals(0,$crawler->filter('html li.tit_after:contains("Trial")')->count());

    }
}

