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
 * Trait for getting appropriate connection
 */
trait ApropriateConnectionTrait
{
    use ConnectionTrait;

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
