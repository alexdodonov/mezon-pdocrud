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
        $this->pdoStatement = $this->pdo->prepare($query, [
            \PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY
        ]);

        if ($this->pdoStatement === false) {
            $errorInfo = $this->pdo->errorInfo();
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
}
