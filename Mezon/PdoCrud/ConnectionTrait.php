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
trait ConnectionTrait
{

    /**
     * Named connections to DB
     *
     * @var array<string, PdoCrud>
     */
    protected static $crud = [];

    /**
     * Method validates dsn fields
     *
     * @param string $connectionName
     *            connection name
     */
    private static function validateDsn(string $connectionName): void
    {
        if (Conf::getConfigValueAsString($connectionName . '/dsn') === '') {
            throw (new \Exception($connectionName . '/dsn not set', -1));
        }

        if (Conf::getConfigValueAsString($connectionName . '/user') === '') {
            throw (new \Exception($connectionName . '/user not set', -1));
        }

        if (Conf::getConfigValueAsString($connectionName . '/password', 'unexists') === 'unexists') {
            throw (new \Exception($connectionName . '/password not set', -1));
        }
    }

    /**
     * Method returns true if the connection exists, false otherwise
     *
     * @param string $connectionName
     *            connection name
     * @return bool true if the connection exists, false otherwise
     */
    private static function dsnExists(string $connectionName): bool
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
    private static function constructConnection(): PdoCrud
    {
        return new PdoCrud();
    }

    /**
     * Method returns database connection
     * If you will pas array of connection names, then the first existing one will be returned
     *
     * @param string $connectionName
     *            Connection name or array of connection names
     * @return PdoCrud connection
     */
    private static function getConnectionScalar(string $connectionName = 'default-db-connection'): PdoCrud
    {
        if (isset(self::$crud[$connectionName])) {
            return self::$crud[$connectionName];
        }

        self::validateDsn($connectionName);

        self::$crud[$connectionName] = self::constructConnection();

        self::$crud[$connectionName]->connect(
            [
                'dsn' => Conf::getConfigValueAsString($connectionName . '/dsn'),
                'user' => Conf::getConfigValueAsString($connectionName . '/user'),
                'password' => Conf::getConfigValueAsString($connectionName . '/password')
            ]);

        return self::$crud[$connectionName];
    }

    /**
     * Method returns database connection
     *
     * @param string[] $connectionName
     *            connection names
     * @return PdoCrud connection
     */
    private static function getConnectionForArray(array $connectionName): PdoCrud
    {
        foreach ($connectionName as $name) {
            if (self::dsnExists($name)) {
                return self::getConnectionScalar($name);
            }
        }

        throw (new \Exception('Connections with names: "' . implode(', ', $connectionName) . '" were not found', - 1));
    }

    /**
     * Method returns database connection
     * If you will pas array of connection names, then the first existing one will be returned
     *
     * @param string|string[]|mixed $connectionName
     *            connection name or array of connection names
     * @return PdoCrud connection
     */
    public static function getConnection($connectionName = 'default-db-connection'): PdoCrud
    {
        if (is_string($connectionName)) {
            return self::getConnectionScalar($connectionName);
        } elseif (is_array($connectionName)) {
            /** @var string[] $connectionName */
            return static::getConnectionForArray($connectionName);
        } else {
            throw (new \Exception('Unsupported type for connection name', -1));
        }
    }

    /**
     * Method sets connection
     *
     * @param ?PdoCrud $connection
     *            new connection or it's mock
     * @param string $connectionName
     *            connection name
     */
    public static function setConnection(?PdoCrud $connection, string $connectionName = 'default-db-connection'): void
    {
        if (null === $connection) {
            unset(self::$crud[$connectionName]);
        } else {
            self::$crud[$connectionName] = $connection;
        }
    }
}
