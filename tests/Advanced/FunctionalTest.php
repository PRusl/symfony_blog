<?php

namespace App\Tests\Advanced;

use App\Tests\Advanced\Fixtures\ArticleFixture;
use App\Tests\Advanced\Fixtures\CategoryFixture;
use App\Tests\Advanced\Fixtures\IFixture;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalTest extends WebTestCase
{
    const REPEAT_COUNT = 3;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @dataProvider provideFixtures
     * @param $fixture
     */
    public function testList($fixture)
    {
        $this->assertEquals(
            $this->getEntitiesCount($fixture),
            $this->getListCount($fixture)
        );
    }

    /**
     * @dataProvider provideFixtures
     * @param IFixture $fixture
     */
    public function testNew(IFixture $fixture)
    {
        $expectedCount = $this->getEntitiesCount($fixture) + 1;

        $url = $fixture->getRequiredPages()->getNew();

        $client = self::createClient();
        $crawler = $client->request('GET', $url);
        $form = $crawler->selectButton('Save')->form();

        $client->submit($form, $this->getFormFields($fixture));

        $this->assertEquals(
            $expectedCount,
            $this->getEntitiesCount($fixture)
        );
    }

    /**
     * @dataProvider provideFixtures
     * @param IFixture $fixture
     */
    public function testEdit(IFixture $fixture)
    {
        $entities = $this->getEntities($fixture);
        $maxCount = count($entities);

        $currentEntity = $entities[rand($maxCount - self::REPEAT_COUNT, $maxCount - 1)];

        $url = $fixture->getRequiredPages()->getEdit();
        $url = str_replace('{id}', $currentEntity->getId(), $url);

        $client = self::createClient();
        $crawler = $client->request('GET', $url);
        $form = $crawler->selectButton('Update')->form();

        $client->submit($form, $this->getFormFields($fixture));

        $this->assertNotEquals(
            $currentEntity,
            $this->getEntity($fixture, $currentEntity->getId())
        );
    }

    /**
     * @dataProvider provideFixtures
     * @param IFixture $fixture
     */
    public function testShow(IFixture $fixture)
    {
        $entities = $this->getEntities($fixture);
        $maxCount = count($entities);

        $currentEntity = $entities[rand($maxCount - self::REPEAT_COUNT, $maxCount - 1)];

        $url = $fixture->getRequiredPages()->getShow();
        $url = str_replace('{id}', $currentEntity->getId(), $url);

        $client = self::createClient();
        $crawler = $client->request('GET', $url);

        $expected = [];
        $returned = [];
        foreach ($fixture->getRequiredFields() as $field) {
            $field = ucfirst($field);
            $method = 'get' . $field;
            $expected[] = $currentEntity->$method();
            $returned[] = $crawler->filter('tr:contains("' . $field . '") > td')->html();
        }

        $this->assertEquals(
            $expected,
            $returned
        );
    }

    /**
     * @dataProvider provideFixtures
     * @param IFixture $fixture
     */
    public function testDelete(IFixture $fixture)
    {
        $entities = $this->getEntities($fixture);
        $maxCount = count($entities);

        $currentEntity = $entities[$maxCount - 1];

        $url = $fixture->getRequiredPages()->getDelete();
        $url = str_replace('{id}', $currentEntity->getId(), $url);

        $client = self::createClient();
        $crawler = $client->request('POST', $url);
        $form = $crawler->selectButton('Delete')->form();

        $client->submit($form);

        $this->assertNull(
            $this->getEntity($fixture, $currentEntity->getId())
        );
    }

    protected function getEntitiesCount(IFixture $fixture): int
    {
        return count($this->getEntities($fixture));
    }

    protected function getEntities(IFixture $fixture): array
    {
        return $this
            ->em
            ->getRepository($fixture->getEntityClass())
            ->findAll();
    }

    protected function getFormFields(IFixture $fixture)
    {
        $baseName = $fixture->getBasePageName();

        $formFields = [];
        foreach ($fixture->getRequiredFields() as $field) {
            $formFields["{$baseName}[{$field}]"] = "test_{$field}_" . rand();
        }

        return $formFields;
    }

    protected function getListCount(IFixture $fixture): int
    {
        $url = $fixture->getRequiredPages()->getList();

        $client = self::createClient();
        $crawler = $client
            ->request('GET', $url)
            ->filter('.table > tbody > tr');

        $count = $crawler->count();

        if ($count === 1 && stripos($crawler->html(), 'no records found') !== false) {
            $count = 0;
        }

        return $count;
    }

    protected function getEntity(IFixture $fixture, int $id)
    {
        return $this
            ->em
            ->getRepository($fixture->getEntityClass())
            ->find($id);
    }

    public function provideFixtures(): array
    {
        $result = [];

        for ($i = 0; $i < self::REPEAT_COUNT; $i++) {
            $result[] = [new CategoryFixture()];
            $result[] = [new ArticleFixture()];
        }

        return $result;
    }

    protected function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
