<?php
namespace Mezon\PdoCrud;

/**
 * Class PdoCrud
 *
 * @package Mezon
 * @subpackage PdoCrud
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/16)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class provides simple CRUD operations
 */
class PdoCrud
{

    /**
     * PDO object
     *
     * @var \PDO
     */
    private $pdo = null;

    use PdoCrudStatement;

    /**
     * Method connects to the database
     *
     * @param array $connnectionData
     *            Connection settings
     * @codeCoverageIgnore
     */
    public function connect(array $connnectionData): void
    {
        // no need to test this single string. assume that PDO developers did it
        $this->pdo = new \PDO($connnectionData['dsn'], $connnectionData['user'], $connnectionData['password']);

        $this->query('SET NAMES utf8');
    }

    /**
     * Method handles request errors
     *
     * @param mixed $result
     *            Query result
     * @param string $query
     *            SQL Query
     * @codeCoverageIgnore
     */
    protected function processQueryError($result, string $query): void
    {
        if ($result === false) {
            $errorInfo = $this->pdo->errorInfo();

            throw (new \Exception($errorInfo[2] . ' in statement ' . $query));
        }
    }

    /**
     *
     * @param string $fieldName
     * @return int
     */
    public function getRecordsCount(string $fieldName = 'records_count'): int
    {
        $records = $this->executeSelect();

        if (empty($records)) {
            return 0;
        } else {
            return $records[0]->$fieldName;
        }
    }

    /**
     * Method compiles lock queries
     *
     * @param array $tables
     *            List of tables
     * @param array $modes
     *            List of lock modes
     * @return string Query
     */
    protected function lockQuery(array $tables, array $modes): string
    {
        $query = [];

        foreach ($tables as $i => $table) {
            $query[] = $table . ' ' . $modes[$i];
        }

        return 'LOCK TABLES ' . implode(' , ', $query);
    }

    /**
     * Method locks tables
     *
     * @param array $tables
     *            List of tables
     * @param array $modes
     *            List of lock modes
     */
    public function lock(array $tables, array $modes): void
    {
        $query = $this->lockQuery($tables, $modes);

        // TODO use prepare/bind/execute
        $result = $this->query($query);

        $this->processQueryError($result, $query);
    }

    /**
     * Method unlocks locked tables
     */
    public function unlock(): void
    {
        // TODO use prepare/bind/execute
        $result = $this->query('UNLOCK TABLES');

        $this->processQueryError($result, 'UNLOCK TABLES');
    }

    /**
     * Method starts transaction
     */
    public function startTransaction(): void
    {
        // setting autocommit off
        // TODO use prepare/bind/execute
        $result = $this->query('SET AUTOCOMMIT = 0');

        $this->processQueryError($result, 'SET AUTOCOMMIT = 0');

        // starting transaction
        // TODO use prepare/bind/execute
        $result = $this->query('START TRANSACTION');

        $this->processQueryError($result, 'START TRANSACTION');
    }

    /**
     * Commiting transaction
     */
    public function commit(): void
    {
        // commit transaction
        // TODO use prepare/bind/execute
        $result = $this->query('COMMIT');

        $this->processQueryError($result, 'COMMIT');

        // setting autocommit on
        // TODO use prepare/bind/execute
        $result = $this->query('SET AUTOCOMMIT = 1');

        $this->processQueryError($result, 'SET AUTOCOMMIT = 1');
    }

    /**
     * Rollback transaction
     */
    public function rollback(): void
    {
        // rollback transaction
        // TODO use prepare/bind/execute
        $result = $this->query('ROLLBACK');

        $this->processQueryError($result, 'ROLLBACK');
    }

    /**
     * Method executes query
     *
     * @param string $query
     *            Query
     * @return mixed Query execution result
     */
    public function query(string $query)
    {
        // @codeCoverageIgnoreStart
        return $this->pdo->query($query);
        // @codeCoverageIgnoreEnd
    }

    /**
     * Method returns id of the last inserted record
     *
     * @return int id of the last inserted record
     */
    public function lastInsertId(): int
    {
        // @codeCoverageIgnoreStart
        return (int) $this->pdo->lastInsertId();
        // @codeCoverageIgnoreEnd
    }

    /**
     * Method destroys connection
     */
    public function __destruct()
    {
        $this->pdo = null;

        unset($this->pdo);
    }
}
