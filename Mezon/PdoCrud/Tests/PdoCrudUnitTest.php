<?php
namespace Mezon\PdoCrud\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\PdoCrud\PdoCrud;

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
     * Testing method compileSetQuery
     */
    public function testCompileSetQuery(): void
    {
        // setup
        $mock = new PdoCrud();
        $record = [
            'a' => 'INC',
            'b' => 'NOW()',
            'c' => null,
            'd' => 1,
            'e' => "s"
        ];

        // test body
        $result = $mock->compileSetQuery($record);

        // assertions
        $this->assertEquals('a = a + 1 , b = NOW() , c = NULL , d = 1 , e = "s"', $result);
    }

    /**
     * Testing exception compileSetQuery
     */
    public function testExceptionCompileSetQuery(): void
    {
        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage('Unsupported data type');

        // setup
        $mock = new PdoCrud();
        $record = [
            'a' => new \stdClass()
        ];

        // test body
        $mock->compileSetQuery($record);
    }
}
