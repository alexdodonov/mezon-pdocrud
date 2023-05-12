<?php
namespace Mezon\PdoCrud\Tests\Internal;

use Mezon\PdoCrud\PdoCrud;
use Mezon\PdoCrud\ApropriateConnectionTrait;

trait ConstructConnectionTrait
{

    use ApropriateConnectionTrait;

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
    private static function constructConnection(): PdoCrud
    {
        if (self::$connection === null) {
            throw (new \Exception('Connection was not setup', - 1));
        }

        return self::$connection;
    }
}
