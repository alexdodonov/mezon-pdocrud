<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\PdoCrud\ApropriateConnectionTrait;
use Mezon\PdoCrud\PdoCrud;

class TraitClient extends TraitClientBase
{
    public function getApropriateConnection(): PdoCrud
    {
        return $this->getConnection([
            'default-db-connection',
            'exact-db-connection'
        ]);
    }
}
