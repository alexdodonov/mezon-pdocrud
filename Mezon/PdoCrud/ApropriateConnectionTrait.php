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
 * Trait for getting appropriate connection
 * 
 * @deprecated since 2021-06-15, use StaticApropriateConnectionTrait
 */
trait ApropriateConnectionTrait
{
    use StaticConnectionTrait;

    /**
     * Method returns appropriate connections
     *
     * @return PdoCrud connection
     */
    public function getApropriateConnection(): PdoCrud
    {
        return $this->getConnection([
            'default-db-connection'
        ]);
    }
}
