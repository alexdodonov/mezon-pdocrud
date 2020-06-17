<?php
namespace Mezon\PdoCrud\Tests;

class PdoCrudMock extends \Mezon\PdoCrud\PdoCrud
{

    public $selectResult = [];

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
        return $this->selectResult;
    }

    /**
     * Counter for update method calls
     *
     * @var integer
     */
    public $updateWasCalledCounter = 0;

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
    public function update(string $tableName, array $record, string $where, int $limit = 10000000): int
    {
        $this->updateWasCalledCounter ++;

        return 1;
    }

    /**
     * Counter for delete method calls
     *
     * @var integer
     */
    public $deleteWasCalledCounter = 0;

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
    public function delete($tableName, $where, $limit = 10000000): int
    {
        $this->deleteWasCalledCounter ++;

        return 1;
    }
}
