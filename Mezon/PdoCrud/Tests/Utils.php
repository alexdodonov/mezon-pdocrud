<?php
namespace Mezon\PdoCrud\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\PdoCrud\PdoCrud;

/**
 * Utilities for unit-tests
 */
class Utils
{

    /**
     * List of methods to be mocked
     *
     * @var array
     */
    public static $mockingMethods = [
        'query',
        'processQueryError',
        'lastInsertId'
    ];

    /**
     * Method returns mock of the PdoCrud object
     * Mocked methods: query, processQueryError, lastInsertId
     *
     * @param \PHPUnit\Framework\TestCase $test
     * @return object
     */
    public static function getMock(TestCase $test): object
    {
        return $test->getMockBuilder(PdoCrud::class)
            ->onlyMethods(self::$mockingMethods)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
