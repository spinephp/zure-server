<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CustomOrder extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
    public function change()
    {

    }
     */

    public function up()
    {
       // create order table
        $table = $this->table('order');
        $table->addColumn('code',  'integer')
            ->addColumn('userid',  'integer')
            ->addColumn('shipdate',  'integer')
            ->addColumn('consigneeid',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('paymentid',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('transportid',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('billtypeid',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('billid',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('billcontentid',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('downpayment',  'integer')
            ->addColumn('guarantee',  'integer')
            ->addColumn('guaranteeperiod',  'integer')
            ->addColumn('carriagecharge',  'float')
            ->addColumn('returnnow',  'float')
            ->addColumn('time',  'datetime')
            ->addColumn('note',  'text', array('limit' => MysqlAdapter::TEXT_REGULAR))
            ->addColumn('stateid',  'integer')
            ->addColumn('contract', 'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('packingtypeid', 'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->create();
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
