<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\PdoCrud\PdoCrud;

class TraitClient extends TraitClientBase
{
    public static function getApropriateConnection(): PdoCrud
    {
        return self::getConnection([
            'default-db-connection',
            'exact-db-connection'
        ]);
    }
}
