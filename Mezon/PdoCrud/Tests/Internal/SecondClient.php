<?php
namespace Mezon\PdoCrud\Tests\Internal;

use Mezon\PdoCrud\PdoCrud;

class SecondClient
{

    /**
     * Connection to be returned
     *
     * @var ?PdoCrud
     */
    private static $connection = null;

    use ConstructConnectionTrait;

    public static function getApropriateConnection(): PdoCrud
    {
        return self::getConnection('second-db-connection');
    }
}
