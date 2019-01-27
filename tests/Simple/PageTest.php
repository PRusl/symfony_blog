<?php

namespace App\Tests\Simple;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUrls()
    {
        return [
            ['/'],
            ['/category/'],
            ['/article/'],
            ['/category/new/'],
            ['/article/new/'],
        ];
    }

    /**
     * @dataProvider provideMainPageLinks
     */
    public function testMainPageLinks($linkName)
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/');

        $linkCount = $crawler
            ->filter('a:contains("' . $linkName . '")')
            ->count()
        ;

        $this->assertEquals(1, $linkCount);

        $client->clickLink($linkName);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideMainPageLinks()
    {
        return [
            ['Categories'],
            ['Articles'],
        ];
    }
}
