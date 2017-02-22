<?php

namespace Acme\Tests;

use Goutte\Client;

trait CrawlTrait
{
    public function crawl($url)
    {
        $client = new Client();
        $client->request('GET', $url);

        return $client->getResponse()->getStatus();
    }
}
