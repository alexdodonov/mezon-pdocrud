<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\Conf\Conf;
use PHPUnit\Framework\TestCase;
use Mezon\PdoCrud\Tests\Internal\ConnectionTraitTests;
use Mezon\PdoCrud\Tests\Internal\FirstClient;
use Mezon\PdoCrud\Tests\Internal\SecondClient;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SeparateConnectionsUnitTest extends ConnectionTraitTests
{

    /**
     *
     * {@inheritdoc}
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        Conf::clear();

        $this->setConnection('first-db-connection');
        $this->setConnection('second-db-connection');
    }

    /**
     *
     * {@inheritdoc}
     * @see \PHPUnit\Framework\TestCase::tearDown()
     */
    protected function tearDown(): void
    {
        Conf::clear();
    }

    /**
     * Testing separate connections for the separate clients
     */
    public function test(): void
    {
        // setup
        $first = new FirstClient(new PdoCrudMock());
        $second = new SecondClient(new PdoCrudMock());

        // test body
        $firstConnection = $first->getApropriateConnection();
        $secondConnection = $second->getApropriateConnection();

        // assertions
        $this->assertTrue($firstConnection !== $secondConnection);
    }
}
