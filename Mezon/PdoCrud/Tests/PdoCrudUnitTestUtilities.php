<?php
namespace Mezon\PdoCrud\Tests;

class PdoCrudUnitTestUtilities
{

    /**
     * Method creates call mock
     *
     * @param callable $mock
     * @param PdoCrudMock $connection
     * @return PdoCrudMock
     */
    public static function makeMethodCallMock(?PdoCrudMock $connection, callable $mock): PdoCrudMock
    {
        if ($connection === null) {
            $connection = new PdoCrudMock();
        }

        $mock($connection);

        return $connection;
    }
}
