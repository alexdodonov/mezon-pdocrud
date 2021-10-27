<?php
namespace Mezon\PdoCrud\Tests;

use Mezon\PdoCrud\PdoCrud;
use Mezon\PdoCrud\PdoCrudStatement;

class PdoCrudMock extends PdoCrud
{

    /**
     * Was the "connect" method called
     *
     * @var boolean
     */
    public $connectWasCalled = false;

    /**
     * Method connects to the database
     *
     * @param array $connnectionData
     *            Connection settings
     * @codeCoverageIgnore
     */
    public function connect(array $connnectionData): void
    {
        $this->connectWasCalled = true;
    }

    /**
     * Selected result
     *
     * @var array
     */
    public $selectResult = [];

    /**
     *
     * {@inheritdoc}
     * @see PdoCrud::select()
     */
    public function select(
        string $fields,
        string $tableNames,
        string $where = '1 = 1',
        int $from = 0,
        int $limit = 1000000): array
    {
        return $this->selectResult;
    }

    /**
     * Counter for update method calls
     *
     * @var integer
     */
    public $updateWasCalledCounter = 0;

    /**
     * Update calls data
     *
     * @var array
     */
    public $updateCalls = [];

    /**
     *
     * {@inheritdoc}
     * @see PdoCrud::update()
     */
    public function update(string $tableName, array $record, string $where, int $limit = 10000000): int
    {
        $this->updateWasCalledCounter ++;

        $this->updateCalls[] = [
            $tableName,
            $record,
            $where,
            $limit
        ];

        return 1;
    }

    /**
     * Locked tables
     *
     * @var array
     */
    public $lockedTables = [];

    /**
     * Locked tables modes
     *
     * @var array
     */
    public $lockedTablesModes = [];

    /**
     *
     * {@inheritdoc}
     * @see PdoCrud::lock()
     */
    public function lock(array $tables, array $modes): void
    {
        $this->lockedTables = $tables;
        $this->lockedTablesModes = $modes;
    }

    /**
     * Special flag wich shows that transaction was started
     *
     * @var boolean
     */
    public $transactionWasStarted = true;

    /**
     *
     * {@inheritdoc}
     * @see PdoCrud::startTransaction()
     */
    public function startTransaction(): void
    {
        $this->transactionWasStarted = true;
    }

    /**
     *
     * {@inheritdoc}
     * @see PdoCrud::rollback()
     */
    public function rollback(): void
    {
        // nop
    }

    /**
     * Special flag wich shows that commit was performed
     *
     * @var boolean
     */
    public $commitWasPerformed = false;

    /**
     *
     * {@inheritdoc}
     * @see PdoCrud::commit()
     */
    public function commit(): void
    {
        $this->commitWasPerformed = true;
    }

    /**
     * Field stores count of insert method was called
     *
     * @var integer
     */
    public $insertWasCalledCounter = 0;

    /**
     * Insert calls
     *
     * @var array
     */
    public $insertCalls = [];

    /**
     *
     * {@inheritdoc}
     * @see PdoCrud::insert()
     */
    public function insert(string $tableName, array $record): int
    {
        $this->insertWasCalledCounter ++;

        $this->insertCalls[] = [
            $tableName,
            $record
        ];

        return 1;
    }

    /**
     * Prepare statements
     * 
     * @var array
     */
    public $prepareStatements = [];

    /**
     *
     * {@inheritdoc}
     * @see PdoCrudStatement::insert()
     */
    public function prepare(string $query): void
    {
        $this->prepareStatements[] = $query;
    }

    /**
     * Field stores count of executeSelect method was called
     *
     * @var integer
     */
    public $executeSelectWasCalledCounter = 0;

    /**
     * List of return values
     *
     * @var array
     */
    public $selectResults = [];

    /**
     *
     * {@inheritdoc}
     * @see PdoCrud::executeSelect()
     */
    public function executeSelect(?array $data = null): array
    {
        $this->executeSelectWasCalledCounter ++;

        $this->selectResults = array_reverse($this->selectResults);

        $return = array_pop($this->selectResults);

        $this->selectResults = array_reverse($this->selectResults);

        return $return;
    }

    /**
     * Binded parameters
     *
     * @var array
     */
    public $bindedParameters = [];

    /**
     *
     * {@inheritdoc}
     * @see PdoCrud::bindParameter()
     */
    public function bindParameter(string $parameter, $variable, int $type = \PDO::PARAM_STR): void
    {
        $this->bindedParameters[] = [
            $parameter,
            $variable,
            $type
        ];
    }

    /**
     * Field stores count of executet method was called
     *
     * @var integer
     */
    public $executeWasCalledCounter = 0;

    /**
     * Method executes SQL query
     *
     * @param ?array $data
     *            query data
     * @codeCoverageIgnore
     */
    public function execute(?array $data = null): void
    {
        $this->executeWasCalledCounter ++;
    }

    /**
     * Result of the lastInsertId call
     *
     * @var integer
     */
    public $lastInsertIdResut = 0;

    /**
     * Method returns id of the last inserted record
     *
     * @return int id of the last inserted record
     */
    public function lastInsertId(): int
    {
        return $this->lastInsertIdResut;
    }

    /**
     * Method returns count of affected rows
     *
     * @return int count of affected rows
     */
    public function rowCount(): int
    {
        return 1;
    }
}
