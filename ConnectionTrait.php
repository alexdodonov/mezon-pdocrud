<?php
namespace Mezon\PdoCrud;

/**
 * Class ConnectionTrait
 *
 * @package Mezon
 * @subpackage PdoCrud
 * @author Dodonov A.A.
 * @version v.1.0 (2020/02/19)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Class provides simple CRUD operations
 */
trait ConnectionTrait
{

    /**
     * Connection to DB.
     */
    protected static $crud = false;

    /**
     * Method validates dsn fields
     *
     * @param string $connectionName
     *            Connectio name
     */
    protected function validateDsn(string $connectionName)
    {
        if (\Mezon\Conf\Conf::getConfigValue($connectionName . '/dsn') === false) {
            throw (new \Exception($connectionName . '/dsn not set'));
        }

        if (\Mezon\Conf\Conf::getConfigValue($connectionName . '/user') === false) {
            throw (new \Exception($connectionName . '/user not set'));
        }

        if (\Mezon\Conf\Conf::getConfigValue($connectionName . '/password') === false) {
            throw (new \Exception($connectionName . '/password not set'));
        }
    }

    /**
     * Contructing connection to database object
     *
     * @return \Mezon\PdoCrud\PdoCrud connection object wich is no initialized
     * @codeCoverageIgnore
     */
    protected function constructConnection(): \Mezon\PdoCrud\PdoCrud
    {
        return new \Mezon\PdoCrud\PdoCrud();
    }

    /**
     * Method returns database connection
     *
     * @param string $connectionName
     *            Connectio name
     * @return mixed connection
     */
    public function getConnection(string $connectionName = 'default-db-connection')
    {
        if (self::$crud !== false) {
            return self::$crud;
        }

        $this->validateDsn($connectionName);

        self::$crud = $this->constructConnection();

        self::$crud->connect(
            [
                'dsn' => \Mezon\Conf\Conf::getConfigValue($connectionName . '/dsn'),
                'user' => \Mezon\Conf\Conf::getConfigValue($connectionName . '/user'),
                'password' => \Mezon\Conf\Conf::getConfigValue($connectionName . '/password')
            ]);

        return self::$crud;
    }

    /**
     * Method sets connection
     *
     * @param mixed $connection
     *            - new connection or it's mock
     */
    public function setConnection($connection): void
    {
        self::$crud = $connection;
    }
}
