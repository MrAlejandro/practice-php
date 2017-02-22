<?php

namespace Acme\Tests;

use Goutte\Client;

class LoggedInTest extends AcmeBaseIntegrationTest
{
    use CrawlTrait;

    public function testLoggedIn()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://localhost/login');
        $responseCode = $client->getResponse()->getStatus();

        $this->assertEquals(200, $responseCode);

        $form = $crawler->selectButton('Sign in')->form();

        $form->setValues([
            'email' => 'me@here.ca',
            'password' => 'verysecret',
        ]);

        $client->submit($form);
        $responseCodeAfterSubmit = $client->getResponse()->getStatus();

        $this->assertEquals(200, $responseCodeAfterSubmit);

        $crawler = $client->request('GET', 'http://localhost/add-testimonial');
        $responseCode = $client->getResponse()->getStatus();
        $this->assertEquals(200, $responseCodeAfterSubmit);
    }

    public function testAddTestimonialWhenNotLoggedIn()
    {
        $responseCode = $this->crawl('http://localhost/add-testimonial');
        $this->assertEquals(404, $responseCode);
    }
}
