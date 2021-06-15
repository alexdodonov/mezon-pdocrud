<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\PdoCrud\PdoCrud;
use Mezon\PdoCrud\StaticApropriateConnectionTrait;

class TraitClientBase
{

    use StaticApropriateConnectionTrait;

    /**
     * Connection to be returned
     *
     * @var ?PdoCrud
     */
    private static $connection = null;

    /**
     * Constructor
     *
     * @param ?PdoCrud $connection
     */
    public function __construct(?PdoCrud $connection = null)
    {
        self::$connection = $connection;
    }

    /**
     * Contructing connection to database object
     *
     * @return PdoCrud connection object wich is no initialized
     * @codeCoverageIgnore
     */
    protected static function constructConnection(): PdoCrud
    {
        return self::$connection;
    }
}
