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
        $obj = new TraitClient(new PdoCrudMock());
        $obj->setConnection(false);

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
        $obj = new TraitClient(new PdoCrudMock());
        $obj->setConnection(false);

        // test body
        $connection = $obj->getApropriateConnection();

        // assertions
        $this->assertInstanceOf(PdoCrudMock::class, $connection);
    }
}
