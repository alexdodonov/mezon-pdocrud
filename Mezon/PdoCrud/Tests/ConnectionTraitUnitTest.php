<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\Conf\Conf;
use Mezon\PdoCrud\PdoCrud;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ConnectionTraitUnitTest extends ConnectionTraitTests
{

    /**
     *
     * {@inheritdoc}
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        Conf::clear();
    }

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
        $obj::setConnectionStatic(null);

        // test body and assertions
        $this->assertInstanceOf(PdoCrudMock::class, $obj::getConnectionStatic());
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
        $obj::setConnectionStatic(null);

        // test body and assertions
        $this->assertInstanceOf(PdoCrudMock::class, $obj::getConnectionStatic([
            'exact-connection'
        ]));
    }

    /**
     * Testing exception when fetching unexisting connection
     */
    public function testExceptionWhenFetchingUnexistingConnection(): void
    {
        // assertions
        $this->expectException(\Exception::class);

        // setup
        $obj = new TraitClient(new PdoCrudMock());
        $obj::setConnectionStatic(null);

        $this->setConnection('first-connection');
        $this->setConnection('second-connection');

        // test body
        $obj::getConnectionStatic([
            'exact-connection'
        ]);
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
     * @param array|object $connectionName
     *            connection name
     * @dataProvider getConnectionForArrayExceptionDataProvider
     */
    public function testGetConnectionForArrayException($connectionName): void
    {
        // assertions
        $this->expectException(\Exception::class);

        // setup
        Conf::deleteConfigValue('exact-connection/dsn');
        $this->setConnection('first-connection');
        $this->setConnection('second-connection');
        $obj = $this->getMock();
        $obj::setConnectionStatic(null);

        // test body
        $obj::getConnectionStatic($connectionName);
    }

    /**
     * Testing method cached getConnection
     */
    public function testGetConnectionCached(): void
    {
        // setup
        $obj = new TraitClient();
        $obj::setConnectionStatic(new PdoCrudMock());

        // test body and assertions
        $this->assertInstanceOf(PdoCrudMock::class, $obj::getConnectionStatic('some-connection-wich-does-not-exists'));
    }
}
