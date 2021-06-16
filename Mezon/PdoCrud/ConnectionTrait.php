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
 *
 * @deprecated since 2021-06-15, use StaticConnectionTrait
 */
trait ConnectionTrait
{

    use StaticConnectionTrait;

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
