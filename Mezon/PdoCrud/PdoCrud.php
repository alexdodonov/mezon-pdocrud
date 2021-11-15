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
     * @var ?\PDO
     */
    private $pdo = null;

    /**
     * Method returns PDO object
     *
     * @return \PDO
     * @codeCoverageIgnore
     */
    private function getPdo(): \PDO
    {
        if ($this->pdo === null) {
            throw (new \Exception('PDO connection was not setup', - 1));
        }

        return $this->pdo;
    }

    use PdoCrudStatement;

    /**
     * Method connects to the database
     *
     * @param array<string, string> $connnectionData
     *            Connection settings
     * @codeCoverageIgnore
     */
    public function connect(array $connnectionData): void
    {
        // no need to test this single string. assume that PDO developers did it
        $this->pdo = new \PDO($connnectionData['dsn'], $connnectionData['user'], $connnectionData['password']);

        $this->prepare('SET NAMES utf8');
        $this->execute();
    }

    /**
     * Method compiles lock queries
     *
     * @param string[] $tables
     *            List of tables
     * @param string[] $modes
     *            List of lock modes
     * @return string Query
     */
    private function lockQuery(array $tables, array $modes): string
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
     * @param string[] $tables
     *            list of tables
     * @param string[] $modes
     *            list of lock modes
     */
    public function lock(array $tables, array $modes): void
    {
        $query = $this->lockQuery($tables, $modes);

        $this->prepare($query);

        $this->execute();
    }

    /**
     * Method unlocks locked tables
     */
    public function unlock(): void
    {
        $this->prepare('UNLOCK TABLES');

        $this->execute();
    }

    /**
     * Method starts transaction
     */
    public function startTransaction(): void
    {
        // setting autocommit off
        $this->prepare('SET AUTOCOMMIT = 0');

        $this->execute();

        // starting transaction
        $this->prepare('START TRANSACTION');

        $this->execute();
    }

    /**
     * Commiting transaction
     */
    public function commit(): void
    {
        // commit transaction
        $this->prepare('COMMIT');

        $this->execute();

        // setting autocommit on
        $this->prepare('SET AUTOCOMMIT = 1');

        $this->execute();
    }

    /**
     * Rollback transaction
     */
    public function rollback(): void
    {
        // rollback transaction
        $this->prepare('ROLLBACK');

        $this->execute();
    }

    /**
     * Method returns id of the last inserted record
     *
     * @return int id of the last inserted record
     */
    public function lastInsertId(): int
    {
        // @codeCoverageIgnoreStart
        return (int) $this->getPdo()->lastInsertId();
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

    /**
     * Method compiles set-query
     *
     * @param
     *            mixed[] $record
     *            Inserting record
     * @return string Compiled query string
     */
    public function compileSetQuery(array $record): string
    {
        // NOTE this method is used not only by this class but also by CrudServiceModel and llaother models wich insert data
        $setFieldsStatement = [];

        /** @var mixed $value */
        foreach ($record as $field => $value) {
            if (is_string($value) && strtoupper($value) === 'INC') {
                $setFieldsStatement[] = $field . ' = ' . $field . ' + 1';
            } elseif (is_string($value) && strtoupper($value) !== 'NOW()') {
                $setFieldsStatement[] = $field . ' = "' . $value . '"';
            } elseif ($value === null) {
                $setFieldsStatement[] = $field . ' = NULL';
            } elseif (is_scalar($value)) {
                $setFieldsStatement[] = $field . ' = ' . $value;
            } else {
                throw (new \Exception('Unsupported data type', - 1));
            }
        }

        return implode(' , ', $setFieldsStatement);
    }
}
