<?php
namespace Mezon\PdoCrud\Tests;

class PdoCrudMock extends \Mezon\PdoCrud\PdoCrud
{

    /**
     * Selected result
     *
     * @var array
     */
    public $selectResult = [];

    /**
     *
     * {@inheritdoc}
     * @see \Mezon\PdoCrud\PdoCrud::select()
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
     *
     * {@inheritdoc}
     * @see \Mezon\PdoCrud\PdoCrud::update()
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
     *
     * {@inheritdoc}
     * @see \Mezon\PdoCrud\PdoCrud::delete()
     */
    public function delete($tableName, $where, $limit = 10000000): int
    {
        $this->deleteWasCalledCounter ++;

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
     * @see \Mezon\PdoCrud\PdoCrud::lock()
     */
    public function lock(array $tables, array $modes): void
    {
        $this->lockedTables = $tables;
        $this->lockedTablesModes = $modes;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Mezon\PdoCrud\PdoCrud::unlock()
     */
    public function unlock(): void
    {
        // nop
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
     * @see \Mezon\PdoCrud\PdoCrud::startTransaction()
     */
    public function startTransaction(): void
    {
        $this->transactionWasStarted = true;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Mezon\PdoCrud\PdoCrud::rollback()
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
     * @see \Mezon\PdoCrud\PdoCrud::commit()
     */
    public function commit(): void
    {
        $this->commitWasPerformed = true;
    }
}
