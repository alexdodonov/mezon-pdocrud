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
 * Trait for getting appropriate connection
 */
trait StaticApropriateConnectionTrait
{
    use StaticConnectionTrait;

    /**
     * Method returns appropriate connections
     *
     * @return PdoCrud connection
     */
    public static function getApropriateConnection(): PdoCrud
    {
        return self::getConnectionStatic([
            'default-db-connection'
        ]);
    }
}
