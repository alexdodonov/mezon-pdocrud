<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\Conf\Conf;
use Mezon\PdoCrud\PdoCrud;
use PHPUnit\Framework\TestCase;

class ConnectionTraitTests extends TestCase
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
     * Setting connection
     *
     * @param string $connectionName
     *            connection name
     */
    protected function setConnection(string $connectionName = 'default-db-connection'): void
    {
        $this->setDsn('dsn', $connectionName);
        $this->setUser('user', $connectionName);
        $this->setPassword('password', $connectionName);
    }
}
