<?php
namespace Mezon\PdoCrud\Tests;

use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class PdoCrudUnitTest extends TestCase
{

    /**
     * Test case setup
     */
    public static function setUpBeforeClass(): void
    {
        Utils::$mockingMethods = [
            'query',
            'processQueryError',
            'lastInsertId'
        ];
    }

    /**
     * Method returns mock
     *
     * @return object PdoCrud mock
     */
    protected function getPdoMock(): object
    {
        $mock = Utils::getMock($this);

        $mock->expects($this->once())
            ->method('query');

        $mock->expects($this->once())
            ->method('processQueryError');

        return $mock;
    }

    /**
     * Testing multiple insertion method
     */
    public function testInsertMultyple(): void
    {
        $mock = $this->getPdoMock();

        $mock->insertMultyple('records', [
            [
                'id' => 1
            ],
            [
                'id' => 2
            ]
        ]);
    }

    /**
     * Testing insertion method
     */
    public function testInsert(): void
    {
        // setup
        $mock = $this->getPdoMock();

        $mock->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(1);

        // test body and assertions
        $mock->insert('records', [
            'id' => 1
        ]);
    }

    /**
     * Testing rollback method
     */
    public function testRollback(): void
    {
        // setup
        $mock = $this->getPdoMock();

        $mock->expects($this->once())
            ->method('query')
            ->willReturn(true);

        // test body and assertions
        $mock->rollback();
    }

    /**
     * Testing commit method
     */
    public function testCommit(): void
    {
        // setup
        $mock = \Mezon\PdoCrud\Tests\Utils::getMock($this);

        $mock->expects($this->exactly(2))
            ->method('query')
            ->willReturn(true);

        // test body and assertions
        $mock->commit();
    }

    /**
     * Testing startTransaction method
     */
    public function testStartTransaction(): void
    {
        // setup
        $mock = \Mezon\PdoCrud\Tests\Utils::getMock($this);

        $mock->expects($this->exactly(2))
            ->method('query')
            ->willReturn(true);

        // test body and assertions
        $mock->startTransaction();
    }

    /**
     * Testing unlock method
     */
    public function testUnlock(): void
    {
        // setup
        $mock = new PdoCrudMock();

        // test body
        $mock->unlock();

        // assertions
        $this->assertEquals('UNLOCK TABLES', $mock->prepareStatements[0]);
        $this->assertCount(1, $mock->prepareStatements);
        $this->assertEquals(1, $mock->executeWasCalledCounter);
    }

    /**
     * Testing lock method
     */
    public function testLock(): void
    {
        // setup
        $mock = $this->getPdoMock();

        // test body and assertions
        $mock->lock([
            'records'
        ], [
            'WRITE'
        ]);
    }

    /**
     * Testing method getRecordsCount
     */
    public function testGetRecordsCount(): void
    {
        // setup
        $pdo = new PdoCrudMock();

        $result = new \stdClass();
        $result->c = 2;
        $pdo->selectResults[] = [
            $result
        ];

        // test body
        $count = $pdo->getRecordsCount('c');

        // assertions
        $this->assertEquals(2, $count);
    }
}
