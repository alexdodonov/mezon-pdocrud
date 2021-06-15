<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\Conf\Conf;
use Mezon\PdoCrud\PdoCrud;
use PHPUnit\Framework\TestCase;

class ConnectionTraitUnitTest extends ConnectionTraitTests
{

    /**
     * Testing insertion method
     */
    public function testGetConnection(): void
    {
        // setup
        $this->setDsn('dsn');
        $this->setUser('user');
        $this->setPassword('password');
        $obj = new TraitClient(new PdoCrudMock());
        $obj->setConnection(false);

        // test body and assertions
        $this->assertInstanceOf(PdoCrudMock::class, $obj->getConnection());
    }

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function getConnectionForArrayDataProvider(): array
    {
        return [
            // #0, the first case, two connections the first one is fetched
            [
                function (): void {
                    // setup method
                    $this->setConnection();
                    $this->setConnection('exact-connection');
                }
            ],
            // #1, the first case, two connections the second one is fetched
            [
                function (): void {
                    // setup method
                    $this->setConnection('exact-connection');
                    $this->setConnection();
                }
            ],
            // #2, the third case, connection was not found
            [
                function (): void {
                    // setup method
                    $this->setConnection('first-connection');
                    $this->setConnection('second-connection');

                    $this->expectException(\Exception::class);
                }
            ]
        ];
    }

    /**
     * Testing method
     *
     * @param callable $setup
     *            setup method
     * @dataProvider getConnectionForArrayDataProvider
     */
    public function testGetConnectionForArray(callable $setup): void
    {
        // setup and assertions
        $setup();
        $obj = new TraitClient(new PdoCrudMock());
        $obj->setConnection(false);

        // test body and assertions
        $this->assertInstanceOf(PdoCrudMock::class, $obj->getConnection([
            'exact-connection'
        ]));
    }

    /**
     * Data provider for the test testGetConnectionForArrayException
     *
     * @return array testing data
     */
    public function getConnectionForArrayExceptionDataProvider(): array
    {
        return [
            [
                [
                    'exact-connection'
                ]
            ],
            [
                new \stdClass()
            ]
        ];
    }

    /**
     * Testing exception for array type connection name
     *
     * @param mixed $connectionNAme
     *            connection name
     * @dataProvider getConnectionForArrayExceptionDataProvider
     */
    public function testGetConnectionForArrayException($connectionName): void
    {
        // TODO add snippet for testing exception with data provider
        // assertions
        $this->expectException(\Exception::class);

        // setup
        Conf::deleteConfigValue('exact-connection/dsn');
        $this->setConnection('first-connection');
        $this->setConnection('second-connection');
        $obj = $this->getMock();
        $obj->setConnection(false);

        // test body
        $obj->getConnection($connectionName);
    }

    /**
     * Testing method cached getConnection
     */
    public function testGetConnectionCached(): void
    {
        // setup
        $obj = new TraitClient();
        $obj->setConnection(new PdoCrudMock());

        // test body and assertions
        $this->assertInstanceOf(PdoCrudMock::class, $obj->getConnection('some-connection-wich-does-not-exists'));
    }
}
