<?php

class TraitClient
{

    use \Mezon\PdoCrud\ConnectionTrait;
}

class ConnectionTraitUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Method returns mock
     *
     * @return object mock
     */
    protected function getPdoMock(): object
    {
        return $this->getMockBuilder(\Mezon\PdoCrud\PdoCrud::class)
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
     * Testing insertion method
     */
    public function testGetConnection(): void
    {
        // setupp
        \Mezon\Conf\Conf::setConfigValue('default-db-connection/dsn', 'dsn');
        \Mezon\Conf\Conf::setConfigValue('default-db-connection/user', 'user');
        \Mezon\Conf\Conf::setConfigValue('default-db-connection/password', 'password');
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
        // setup
        \Mezon\Conf\Conf::deleteConfigValue('default-db-connection/dsn');
        \Mezon\Conf\Conf::setConfigValue('default-db-connection/user', 'user');
        \Mezon\Conf\Conf::setConfigValue('default-db-connection/password', 'password');
        $mock = $this->getMock();

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
        \Mezon\Conf\Conf::setConfigValue('default-db-connection/dsn', 'dsn');
        \Mezon\Conf\Conf::deleteConfigValue('default-db-connection/user');
        \Mezon\Conf\Conf::setConfigValue('default-db-connection/password', 'password');
        $mock = $this->getMock();

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
        \Mezon\Conf\Conf::setConfigValue('default-db-connection/dsn', 'dsn');
        \Mezon\Conf\Conf::setConfigValue('default-db-connection/user', 'user');
        \Mezon\Conf\Conf::deleteConfigValue('default-db-connection/password');
        $mock = $this->getMock();

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $mock->getConnection();
    }
}
