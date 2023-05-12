<?php
namespace Mezon\PdoCrud\Tests\Internal;

use Mezon\PdoCrud\PdoCrud;

class FirstClient
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
        return self::getConnection('first-db-connection');
    }
}
