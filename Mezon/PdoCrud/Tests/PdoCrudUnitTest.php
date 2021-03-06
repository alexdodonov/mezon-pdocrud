<?php
namespace Mezon\PdoCrud\Tests;

use PHPUnit\Framework\TestCase;

/** @psalm-suppress PropertyNotSetInConstructor */
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
        $mock = $this->getPdoMock();

        // test body and assertions
        $mock->unlock();
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
     * Testing delete method
     */
    public function testDelete(): void
    {
        // setup
        $mock = \Mezon\PdoCrud\Tests\Utils::getMock($this);

        $mock->expects($this->exactly(1))
            ->method('query')
            ->willReturn(new ResultMock());

        // test body and assertions
        $mock->delete('records', 'id=1');
    }

    /**
     * Testing update method
     */
    public function testUpdate(): void
    {
        // setup
        $mock = \Mezon\PdoCrud\Tests\Utils::getMock($this);
        $mock->expects($this->exactly(1))
            ->method('query')
            ->willReturn(new ResultMock());

        // test body and assertions
        $mock->update('som-record', [], '1=1');
    }

    /**
     * Testing select method
     */
    public function testSelect(): void
    {
        // setup
        $queryResultMock = $this->getMockBuilder(\PDOStatement::class)
            ->onlyMethods([
            'fetchAll'
        ])
            ->disableOriginalConstructor()
            ->getMock();
        $queryResultMock->method('fetchAll')->willReturn([
            [],
            []
        ]);

        $mock = \Mezon\PdoCrud\Tests\Utils::getMock($this);
        $mock->expects($this->exactly(1))
            ->method('query')
            ->willReturn($queryResultMock);

        // test body
        $result = $mock->select('som-record', '', '1=1');

        // assertions
        $this->assertCount(2, $result);
    }
}
