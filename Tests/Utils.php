<?php
namespace Mezon\PdoCrud\Tests;

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
    public static function getMock(\PHPUnit\Framework\TestCase $test): object
    {
        return $test->getMockBuilder(\Mezon\PdoCrud\PdoCrud::class)
            ->setMethods(self::$mockingMethods)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
