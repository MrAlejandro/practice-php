<?php

namespace Acme\Tests;

use Goutte\Client;

class PublicPagesTest extends AcmeBaseIntegrationTest
{
    use CrawlTrait;

    /**
     * @dataProvider provideUrls
     */
    public function testPages($urlToTest)
    {
        $responseCode = $this->crawl('http://localhost' . $urlToTest);
        $this->assertEquals(200, $responseCode);
    }

    public function testPageNotFound()
    {
        $responseCode = $this->crawl('http://localhost/abcd');
        $this->assertEquals(404, $responseCode);
    }

    public function testLoginPage()
    {
        $responseCode = $this->crawl('http://localhost/login');
        $this->assertEquals(200, $responseCode);
    }

    public function provideUrls()
    {
        return [
            ['/'],
            ['/about-acme'],
            ['/account-activated'],
            ['/success'],
        ];
    }
}
