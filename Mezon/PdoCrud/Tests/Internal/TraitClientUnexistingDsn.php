<?php
namespace Mezon\PdoCrud\Tests\Internal;

use Mezon\PdoCrud\PdoCrud;

class TraitClientUnexistingDsn extends TraitClientBase
{

    public static function getApropriateConnection(): PdoCrud
    {
        return TraitClientUnexistingDsn::getConnection([
            'unexisting-db-connection'
        ]);
    }
}
