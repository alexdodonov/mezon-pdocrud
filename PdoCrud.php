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
     */
    protected $pdo = false;

    /**
     * Method connects to the database
     *
     * @param array $connnectionData
     *            Connection settings
     * @codeCoverageIgnore
     */
    public function connect(array $connnectionData)
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
    protected function processQueryError($result, string $query)
    {
        if ($result === false) {
            $errorInfo = $this->pdo->errorInfo();

            throw (new \Exception($errorInfo[2] . ' in statement ' . $query));
        }
    }

    /**
     * Getting records
     *
     * @param string $fields
     *            List of fields
     * @param string $tableNames
     *            List of tables
     * @param string $where
     *            Condition
     * @param int $from
     *            First record in query
     * @param int $limit
     *            Count of records
     * @return array List of records
     */
    public function select(
        string $fields,
        string $tableNames,
        string $where = '1 = 1',
        int $from = 0,
        int $limit = 1000000): array
    {
        $query = "SELECT $fields FROM $tableNames WHERE $where LIMIT " . intval($from) . ' , ' . intval($limit);

        $result = $this->query($query);

        $this->processQueryError($result, $query);

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Method compiles set-query
     *
     * @param array $record
     *            Inserting record
     * @return string Compiled query string
     */
    protected function setQuery(array $record): string
    {
        $setFieldsStatement = [];

        foreach ($record as $field => $value) {
            if (is_string($value) && strtoupper($value) === 'INC') {
                $setFieldsStatement[] = $field . ' = ' . $field . ' + 1';
            } elseif (is_string($value) && strtoupper($value) !== 'NOW()') {
                $setFieldsStatement[] = $field . ' = "' . $value . '"';
            } elseif ($value === null) {
                $setFieldsStatement[] = $field . ' = NULL';
            } else {
                $setFieldsStatement[] = $field . ' = ' . $value;
            }
        }

        return implode(' , ', $setFieldsStatement);
    }

    /**
     * Method compiles set-multyple-query
     *
     * @param array $records
     *            Inserting records
     * @return string Compiled query string
     */
    protected function setMultypleQuery(array $records)
    {
        $query = '( ' . implode(' , ', array_keys($records[0])) . ' ) VALUES ';

        $values = [];

        foreach ($records as $record) {
            $values[] = "( '" . implode("' , '", array_values($record)) . "' )";
        }

        return $query . implode(' , ', $values);
    }

    /**
     * Updating records
     *
     * @param string $tableName
     *            Table name
     * @param array $record
     *            Updating records
     * @param string $where
     *            Condition
     * @param int $limit
     *            Liti for afffecting records
     * @return int Count of updated records
     */
    public function update(string $tableName, array $record, string $where, int $limit = 10000000)
    {
        $query = 'UPDATE ' . $tableName . ' SET ' . $this->setQuery($record) . ' WHERE ' . $where . ' LIMIT ' . $limit;

        $result = $this->query($query);

        $this->processQueryError($result, $query);

        return $result->rowCount();
    }

    /**
     * Deleting records
     *
     * @param string $tableName
     *            Table name
     * @param string $where
     *            Condition
     * @param int $limit
     *            Liti for afffecting records
     * @return int Count of deleted records
     */
    public function delete($tableName, $where, $limit = 10000000)
    {
        $query = 'DELETE FROM ' . $tableName . ' WHERE ' . $where . ' LIMIT ' . intval($limit);

        $result = $this->query($query);

        $this->processQueryError($result, $query);

        return $result->rowCount();
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
    public function lock(array $tables, array $modes)
    {
        $query = $this->lockQuery($tables, $modes);

        $result = $this->query($query);

        $this->processQueryError($result, $query);
    }

    /**
     * Method unlocks locked tables
     */
    public function unlock()
    {
        $result = $this->query('UNLOCK TABLES');

        $this->processQueryError($result, 'UNLOCK TABLES');
    }

    /**
     * Method starts transaction
     */
    public function startTransaction()
    {
        // setting autocommit off
        $result = $this->query('SET AUTOCOMMIT = 0');

        $this->processQueryError($result, 'SET AUTOCOMMIT = 0');

        // starting transaction
        $result = $this->query('START TRANSACTION');

        $this->processQueryError($result, 'START TRANSACTION');
    }

    /**
     * Commiting transaction
     */
    public function commit()
    {
        // commit transaction
        $result = $this->query('COMMIT');

        $this->processQueryError($result, 'COMMIT');

        // setting autocommit on
        $result = $this->query('SET AUTOCOMMIT = 1');

        $this->processQueryError($result, 'SET AUTOCOMMIT = 1');
    }

    /**
     * Rollback transaction
     */
    public function rollback()
    {
        // rollback transaction
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
     * @return string id of the last inserted record
     */
    public function lastInsertId()
    {
        // @codeCoverageIgnoreStart
        return $this->pdo->lastInsertId();
        // @codeCoverageIgnoreEnd
    }

    /**
     * Method inserts record
     *
     * @param string $tableName
     *            Table name
     * @param array $record
     *            Inserting record
     * @return int New record's id
     */
    public function insert(string $tableName, array $record): int
    {
        $query = 'INSERT ' . $tableName . ' SET ' . $this->setQuery($record);

        $result = $this->query($query);

        $this->processQueryError($result, $query);

        return $this->lastInsertId();
    }

    /**
     * Method inserts record
     *
     * @param string $tableName
     *            Table name
     * @param array $records
     *            Inserting records
     * @return int New record's id
     */
    public function insertMultyple(string $tableName, array $records)
    {
        $query = 'INSERT INTO ' . $tableName . ' ' . $this->setMultypleQuery($records) . ';';

        $result = $this->query($query);

        $this->processQueryError($result, $query);

        return 0;
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
