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
trait PdoCrudStatement
{

    /**
     * PDO statement
     *
     * @var \PDOStatement
     */
    private $pdoStatement = null;

    /**
     * Method sets safe query
     *
     * @param string $query
     *            safe query
     * @codeCoverageIgnore
     */
    public function prepare(string $query): void
    {
        $this->pdoStatement = $this->getPdo()->prepare($query, [
            \PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY
        ]);

        if ($this->pdoStatement === false) {
            /**
             *
             * @var array{0: string, 1: string, 2: string}
             */
            $errorInfo = $this->getPdo()->errorInfo();
            throw (new \Exception('Query "' . $query . '" was not prepared. ' . $errorInfo[2], - 1));
        }
    }

    /**
     * Method binds parameters to query
     *
     * @param string $parameter
     *            name of the parameter
     * @param mixed $variable
     *            value
     * @param int $type
     *            parameter type
     * @codeCoverageIgnore
     */
    public function bindParameter(string $parameter, $variable, int $type = \PDO::PARAM_STR): void
    {
        $this->pdoStatement->bindParam($parameter, $variable, $type);
    }

    /**
     * Method executes SQL query
     *
     * @param ?array $data
     *            query data
     * @codeCoverageIgnore
     */
    public function execute(?array $data = null): void
    {
        if ($this->pdoStatement->execute($data) === false) {
            /**
             * 
             * @var array{0: string, 1: string, 2: string}
             */
            $info = $this->pdoStatement->errorInfo();

            throw (new \Exception($info[2], - 1));
        }
    }

    /**
     * Method executes select query and fetches results
     *
     * @param ?array $data
     *            query data
     * @return object[] query result as an array of objects
     * @codeCoverageIgnore
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function executeSelect(?array $data = null): array
    {
        $this->execute($data);

        return $this->pdoStatement->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Method executes select query and fetches results
     *
     * @param ?array $data
     *            query data
     * @return array query result as an array of objects
     * @codeCoverageIgnore
     * @deprecated Deprecated since 2020-11-21, use executeSelect
     */
    public function execSelect(?array $data = null): array
    {
        return $this->executeSelect($data);
    }

    /**
     * Method returns count of affected rows
     *
     * @return int count of affected rows
     * @codeCoverageIgnore
     */
    public function rowCount(): int
    {
        return $this->pdoStatement->rowCount();
    }
}
