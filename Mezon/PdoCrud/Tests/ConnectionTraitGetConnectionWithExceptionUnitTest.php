<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\Conf\Conf;
use Mezon\PdoCrud\PdoCrud;
use PHPUnit\Framework\TestCase;

class ConnectionTraitGetConnectionWithExceptionUnitTest extends ConnectionTraitTests
{

    /**
     * Data provider for the test testGetConnectionException
     *
     * @return array testing data
     */
    public function getConnectionExceptionDataProvider(): array
    {
        return [
            [
                function () {
                    Conf::deleteConfigValue('default-db-connection/dsn');
                    $this->setUser('user');
                    $this->setPassword('password');
                    $mock = $this->getMock();
                    $mock->setConnection(false);
                    return $mock;
                }
            ],
            [
                function () {
                    $this->setDsn('dsn');
                    Conf::deleteConfigValue('default-db-connection/user');
                    $this->setPassword('password');
                    $mock = $this->getMock();
                    $mock->setConnection(false);
                    return $mock;
                }
            ],
            [
                function () {
                    $this->setDsn('dsn');
                    $this->setUser('user');
                    Conf::deleteConfigValue('default-db-connection/password');
                    $mock = $this->getMock();
                    $mock->setConnection(false);
                    return $mock;
                }
            ]
        ];
    }

    /**
     * Testing exception
     *
     * @param callable $setup
     *            setup method
     * @dataProvider getConnectionExceptionDataProvider
     */
    public function testGetConnectionException(callable $setup): void
    {
        // assertions
        $this->expectException(\Exception::class);

        // setup
        $connection = $setup();

        // test body
        $connection->getConnection();
    }
}
