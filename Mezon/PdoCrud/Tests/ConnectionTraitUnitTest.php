<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\Conf\Conf;
use Mezon\PdoCrud\PdoCrud;
use PHPUnit\Framework\TestCase;

class ConnectionTraitUnitTest extends TestCase
{

    /**
     * Method returns mock
     *
     * @return object mock
     */
    protected function getPdoMock(): object
    {
        return $this->getMockBuilder(PdoCrud::class)
            ->setMethods([
            'connect'
        ])
            ->getMock();
    }

    /**
     * Method returns mock
     *
     * @return object mock
     */
    protected function getMock(): object
    {
        return $this->getMockBuilder(TraitClient::class)
            ->setMethods([
            'constructConnection'
        ])
            ->getMock();
    }

    /**
     * Method sets dsn
     *
     * @param string $dsn
     *            dsn
     * @param string $connectionName
     *            connection name
     */
    protected function setDsn(string $dsn, string $connectionName = 'default-db-connection'): void
    {
        Conf::setConfigValue($connectionName . '/dsn', $dsn);
    }

    /**
     * Method sets user
     *
     * @param string $user
     *            user
     * @param string $connectionName
     *            connection name
     */
    protected function setUser(string $user, string $connectionName = 'default-db-connection'): void
    {
        Conf::setConfigValue($connectionName . '/user', $user);
    }

    /**
     * Method sets password
     *
     * @param string $password
     *            password
     * @param string $connectionName
     *            connection name
     */
    protected function setPassword(string $password, string $connectionName = 'default-db-connection'): void
    {
        Conf::setConfigValue($connectionName . '/password', $password);
    }

    /**
     * Testing insertion method
     */
    public function testGetConnection(): void
    {
        // setupp
        $this->setDsn('dsn');
        $this->setUser('user');
        $this->setPassword('password');
        $mock = $this->getMock();

        $mock->expects($this->once())
            ->method('constructConnection')
            ->willReturn($this->getPdoMock());

        // test body and assertionss
        $mock->getConnection(); // creating connection object
        $mock->getConnection(); // getting created object
    }

    /**
     * Asserting exception if dsn is not set
     */
    public function testDsnException(): void
    {
        // TODO join these tests in one with data provider
        // setup
        Conf::deleteConfigValue('default-db-connection/dsn');
        $this->setUser('user');
        $this->setPassword('password');
        $mock = $this->getMock();
        $mock->setConnection(false);

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $mock->getConnection();
    }

    /**
     * Asserting exception if user is not set
     */
    public function testUserException(): void
    {
        // setup
        $this->setDsn('dsn');
        Conf::deleteConfigValue('default-db-connection/user');
        $this->setPassword('password');
        $mock = $this->getMock();
        $mock->setConnection(false);

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $mock->getConnection();
    }

    /**
     * Asserting exception if password is not set
     */
    public function testPasswordException(): void
    {
        // setup
        $this->setDsn('dsn');
        $this->setUser('user');
        Conf::deleteConfigValue('default-db-connection/password');
        $mock = $this->getMock();
        $mock->setConnection(false);

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $mock->getConnection();
    }

    /**
     * Setting connection
     *
     * @param string $connectionName
     *            connection name
     */
    private function setConnection(string $connectionName = 'default-db-connection'): void
    {
        $this->setDsn('dsn', $connectionName);
        $this->setUser('user', $connectionName);
        $this->setPassword('password', $connectionName);
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
        $mock = $this->getMock();
        $mock->setConnection(false);
        $mock->expects($this->once())
            ->method('constructConnection')
            ->willReturn($this->getPdoMock());

        // test body
        $mock->getConnection([
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
        $mock = $this->getMock();
        $mock->setConnection(false);

        // test body
        $mock->getConnection($connectionName);
    }
}
