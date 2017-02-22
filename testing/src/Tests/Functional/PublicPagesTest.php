<?php

namespace Acme\Tests;

class PublicPagesTest extends AcmeBaseIntegrationTest
{
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

    public function crawl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        return curl_getinfo($ch, CURLINFO_HTTP_CODE);
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
