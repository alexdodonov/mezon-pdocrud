<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
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
        $obj = new TraitClient(new PdoCrudMock());
        $obj::setConnection(null);

        // test body
        $connection = $obj->getApropriateConnection();

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
        $obj = new TraitClientBase(new PdoCrudMock());
        $obj::setConnection(null);

        // test body
        $connection = $obj->getApropriateConnection();

        // assertions
        $this->assertInstanceOf(PdoCrudMock::class, $connection);
    }

    /**
     * Testing method
     */
    public function testGetApropriateConnectionException(): void
    {
        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(-1);
        $this->expectExceptionMessage('Connections with names: "unexisting-db-connection" were not found');
        
        // setup
        $this->setConnection('default-db-connection');
        $obj = new TraitClientUnexistingDsn();
        $obj->setConnection(null);

        // test body
        $obj->getApropriateConnection();
    }
}
