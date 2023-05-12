<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\Conf\Conf;
use Mezon\PdoCrud\Tests\Internal\TraitClient;
use PHPUnit\Framework\TestCase;
use Mezon\PdoCrud\Tests\Internal\ConnectionTraitTests;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ConnectionTraitGetConnectionWithExceptionUnitTest extends ConnectionTraitTests
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
     *
     * {@inheritdoc}
     * @see \PHPUnit\Framework\TestCase::tearDown()
     */
    protected function tearDown(): void
    {
        Conf::clear();
    }

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
                    $mock = new TraitClient();
                    $mock::setConnection(null);
                    return $mock;
                },
                'default-db-connection/dsn not set'
            ],
            [
                function () {
                    $this->setDsn('dsn');
                    Conf::deleteConfigValue('default-db-connection/user');
                    $this->setPassword('password');
                    $mock = new TraitClient();
                    $mock::setConnection(null);
                    return $mock;
                },
                'default-db-connection/user not set'
            ],
            [
                function () {
                    $this->setDsn('dsn');
                    $this->setUser('user');
                    Conf::deleteConfigValue('default-db-connection/password');
                    $mock = new TraitClient();
                    $mock::setConnection(null);
                    return $mock;
                },
                'default-db-connection/password not set'
            ]
        ];
    }

    /**
     * Testing exception
     *
     * @param callable():TraitClient $setup
     *            setup method
     * @param string $message
     *            exception message
     * @dataProvider getConnectionExceptionDataProvider
     */
    public function testGetConnectionException(callable $setup, string $message): void
    {
        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage($message);

        // setup
        $connection = $setup();

        // test body
        $connection::getConnection();
    }
}
