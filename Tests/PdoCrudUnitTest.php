<?php

class ResultMock
{

    public function rowCount(): int
    {
        return 0;
    }
}

class PdoCrudUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Method returns not setup mock
     *
     * @return object PdoCrud not setup mock
     */
    protected function getUnsetupPdoMock(): object
    {
        $mock = $this->getMockBuilder(\Mezon\PdoCrud\PdoCrud::class)
            ->setMethods([
            'query',
            'processQueryError',
            'lastInsertId'
        ])
            ->setConstructorArgs([])
            ->getMock();

        return $mock;
    }

    /**
     * Method returns mock
     *
     * @return object PdoCrud mock
     */
    protected function getPdoMock(): object
    {
        $mock = $this->getUnsetupPdoMock();

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
        $mock = $this->getUnsetupPdoMock();

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
        $mock = $this->getUnsetupPdoMock();

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
        $mock = $this->getUnsetupPdoMock();

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
        $mock = $this->getUnsetupPdoMock();
        $mock->expects($this->exactly(1))
            ->method('query')
            ->willReturn(new ResultMock());

        // test body and assertions
        $mock->update('som-record', [], '1=1');
    }
}
