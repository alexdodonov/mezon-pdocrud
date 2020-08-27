<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\Conf\Conf;

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
     * Method sets dsn
     * 
     * @param string $dsn dsn
     */
    protected function setDsn(string $dsn):void{
        Conf::setConfigValue('default-db-connection/dsn', $dsn);
    }

    /**
     * Method sets user
     * 
     * @param string $user user
     */
    protected function setUser(string $user):void{
        Conf::setConfigValue('default-db-connection/user', $user);
    }

    /**
     * Method sets password
     * 
     * @param string $password password
     */
    protected function setPassword(string $password):void{
        Conf::setConfigValue('default-db-connection/password', $password);
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
        // setup
        \Mezon\Conf\Conf::deleteConfigValue('default-db-connection/dsn');
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
        \Mezon\Conf\Conf::deleteConfigValue('default-db-connection/user');
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
        \Mezon\Conf\Conf::deleteConfigValue('default-db-connection/password');
        $mock = $this->getMock();
        $mock->setConnection(false);

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $mock->getConnection();
    }
}
