<?php
namespace Mezon\PdoCrud;

use Mezon\Conf\Conf;

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
 * Trait for getting connections
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
    protected function validateDsn(string $connectionName): void
    {
        if (Conf::getConfigValue($connectionName . '/dsn') === false) {
            throw (new \Exception($connectionName . '/dsn not set'));
        }

        if (Conf::getConfigValue($connectionName . '/user') === false) {
            throw (new \Exception($connectionName . '/user not set'));
        }

        if (Conf::getConfigValue($connectionName . '/password') === false) {
            throw (new \Exception($connectionName . '/password not set'));
        }
    }

    /**
     * Method returns true if the connection exists, false otherwise
     *
     * @param string $connectionName
     *            connection name
     * @return bool true if the connection exists, false otherwise
     */
    protected function dsnExists(string $connectionName): bool
    {
        try {
            $this->validateDsn($connectionName);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Contructing connection to database object
     *
     * @return PdoCrud connection object wich is no initialized
     * @codeCoverageIgnore
     */
    protected function constructConnection(): PdoCrud
    {
        return new PdoCrud();
    }

    /**
     * Method returns database connection.
     * If you will pas array of connection names, then the first existing one will be returned
     *
     * @param string|array $connectionName
     *            Connectioт name or array of connection names.
     * @return mixed connection
     */
    private function getConnectionScalar(string $connectionName = 'default-db-connection')
    {
        $this->validateDsn($connectionName);

        self::$crud = $this->constructConnection();

        self::$crud->connect(
            [
                'dsn' => Conf::getConfigValue($connectionName . '/dsn'),
                'user' => Conf::getConfigValue($connectionName . '/user'),
                'password' => Conf::getConfigValue($connectionName . '/password')
            ]);

        return self::$crud;
    }

    /**
     * Method returns database connection.
     * If you will pas array of connection names, then the first existing one will be returned
     *
     * @param string|array $connectionName
     *            Connectioт name or array of connection names.
     * @return mixed connection
     */
    public function getConnection($connectionName = 'default-db-connection')
    {
        if (self::$crud !== false) {
            return self::$crud;
        }

        if (is_string($connectionName)) {
            return $this->getConnectionScalar($connectionName);
        } elseif (is_array($connectionName)) {
            foreach ($connectionName as $name) {
                if ($this->dsnExists($name)) {
                    return $this->getConnectionScalar($name);
                }
            }

            // TODO remove srialize and make normal error message
            throw (new \Exception('Connection with name "' . serialize($connectionName) . '" was not found'));
        } else {
            throw (new \Exception('Unsupported type for connection name'));
        }
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
