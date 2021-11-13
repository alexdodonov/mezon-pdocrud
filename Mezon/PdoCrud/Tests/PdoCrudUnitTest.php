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
     * Testing rollback method
     */
    public function testRollback(): void
    {
        // setup
        $mock = new PdoCrudMock();

        // test body
        $mock->rollback();

        // assertions
        $this->assertTrue($mock->rolledBack);
    }

    /**
     * Testing commit method
     */
    public function testCommit(): void
    {
        // setup
        $mock = new PdoCrudMock();

        // test body
        $mock->commit();

        // assertions
        $this->assertTrue($mock->commitWasPerformed);
    }

    /**
     * Testing startTransaction method
     */
    public function testStartTransaction(): void
    {
        // setup
        $mock = new PdoCrudMock();

        // test body
        $mock->startTransaction();

        // assertions
        $this->assertTrue($mock->transactionWasStarted);
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
        $mock = new PdoCrudMock();
        $mock->lockedTables = [];
        $mock->lockedTablesModes = [];

        // test body
        $mock->lock([
            'records'
        ], [
            'WRITE'
        ]);

        // assertions
        $this->assertEquals([
            'records'
        ], $mock->lockedTables);
        $this->assertEquals([
            'WRITE'
        ], $mock->lockedTablesModes);
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
