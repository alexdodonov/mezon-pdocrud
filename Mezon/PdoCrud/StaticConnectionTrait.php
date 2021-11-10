<?php
namespace Mezon\PdoCrud;

use Mezon\Conf\Conf;

/**
 * Class ConnectionTrait
 *
 * @package Mezon
 * @subpackage PdoCrud
 * @author Dodonov A.A.
 * @version v.1.0 (2021/06/15)
 * @copyright Copyright (c) 2021, aeon.org
 */

/**
 * Trait for getting static connections
 */
trait StaticConnectionTrait
{

    /**
     * Connection to DB.
     */
    protected static $crud = null;

    /**
     * Method validates dsn fields
     *
     * @param string $connectionName
     *            Connectio name
     */
    protected static function validateDsn(string $connectionName): void
    {
        if (Conf::getConfigValueAsString($connectionName . '/dsn') === '') {
            throw (new \Exception($connectionName . '/dsn not set'));
        }

        if (Conf::getConfigValueAsString($connectionName . '/user') === '') {
            throw (new \Exception($connectionName . '/user not set'));
        }

        if (Conf::getConfigValueAsString($connectionName . '/password') === '') {
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
    protected static function dsnExists(string $connectionName): bool
    {
        try {
            self::validateDsn($connectionName);
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
    protected static function constructConnection(): PdoCrud
    {
        return new PdoCrud();
    }

    /**
     * Method returns database connection.
     * If you will pas array of connection names, then the first existing one will be returned
     *
     * @param string $connectionName
     *            Connection name or array of connection names.
     * @return PdoCrud connection
     */
    private static function getConnectionScalar(string $connectionName = 'default-db-connection'): PdoCrud
    {
        self::validateDsn($connectionName);

        self::$crud = self::constructConnection();

        self::$crud->connect(
            [
                'dsn' => Conf::getConfigValueAsString($connectionName . '/dsn'),
                'user' => Conf::getConfigValueAsString($connectionName . '/user'),
                'password' => Conf::getConfigValueAsString($connectionName . '/password')
            ]);

        return self::$crud;
    }

    /**
     * Method returns database connection.
     * If you will pas array of connection names, then the first existing one will be returned
     *
     * @param string|array|mixed $connectionName
     *            Connection name or array of connection names.
     * @return PdoCrud connection
     */
    public static function getConnectionStatic($connectionName = 'default-db-connection'): PdoCrud
    {
        if (self::$crud !== null) {
            return self::$crud;
        }

        if (is_string($connectionName)) {
            return self::getConnectionScalar($connectionName);
        } elseif (is_array($connectionName)) {
            foreach ($connectionName as $name) {
                if (self::dsnExists($name)) {
                    return self::getConnectionScalar($name);
                }
            }

            throw (new \Exception('Connection with names: "' . implode(', ', $connectionName) . '" were not found'));
        } else {
            throw (new \Exception('Unsupported type for connection name'));
        }
    }

    /**
     * Method sets connection
     *
     * @param ?PdoCrud $connection
     *            new connection or it's mock
     */
    public static function setConnectionStatic(?PdoCrud $connection): void
    {
        self::$crud = $connection;
    }

    /**
     * Method returns database connection.
     * If you will pas array of connection names, then the first existing one will be returned
     *
     * @param string|array $connectionName
     *            Connectioт name or array of connection names.
     * @return PdoCrud connection
     */
    public function getConnection($connectionName = 'default-db-connection'): PdoCrud
    {
        return self::getConnectionStatic($connectionName);
    }

    /**
     * Method sets connection
     *
     * @param ?PdoCrud $connection
     *            new connection or it's mock
     */
    public function setConnection(?PdoCrud $connection): void
    {
        self::setConnectionStatic($connection);
    }
}
