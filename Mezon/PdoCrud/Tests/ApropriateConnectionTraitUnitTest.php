<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\Conf\Conf;
use Mezon\PdoCrud\PdoCrud;
use PHPUnit\Framework\TestCase;

class ApropriateConnectionTraitUnitTest extends ConnectionTraitTests
{

    /**
     * Testing method getApropriateConnection
     */
    public function testGetApropriateConnectionMultiple(): void
    {
        // setup
        Conf::deleteConfigValue('default-db-connection/dsn');
        $this->setConnection('exact-db-connection');
        $mock = $this->getMock();
        $mock->setConnection(false);
        $mock->expects($this->once())
            ->method('constructConnection')
            ->willReturn(new PdoCrudMock());

        // test body
        $connection = $mock->getApropriateConnection('exact-db-connection');

        // assertions
        $this->assertInstanceOf(PdoCrudMock::class, $connection);
    }

    /**
     * Testing method getApropriateConnection without overriding of the method getApropriateConnection
     */
    public function testGetApropriateConnectionBase(): void
    {
        // setup
        $this->setConnection('default-db-connection');
        $mock = $this->getMockBase();
        $mock->setConnection(false);
        $mock->expects($this->once())
            ->method('constructConnection')
            ->willReturn(new PdoCrudMock());

        // test body
        $connection = $mock->getApropriateConnection('default-db-connection');

        // assertions
        $this->assertInstanceOf(PdoCrudMock::class, $connection);
    }
}
