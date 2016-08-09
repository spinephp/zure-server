<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Order extends AbstractMigration
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
       // create order packing tray carton table
        $table = $this->table('order');
        $table->addColumn('code', 'integer', array('signed'=>false,'limit'=>6))
            ->addColumn('userid', 'integer')
            ->addColumn('shipdate', 'integer', array('signed'=>false,'limit'=>4,'default'=>0))
            ->addColumn('consigneeid', 'integer')
            ->addColumn('paymentid', 'integer')
            ->addColumn('transportid', 'integer', array('signed'=>false,'limit'=>2,'default'=>1))
            ->addColumn('billtypeid', 'integer', array('signed'=>false,'limit'=>2))
            ->addColumn('billid', 'integer', array('signed'=>false,'limit'=>2))
            ->addColumn('billcontentid', 'integer', array('signed'=>false,'limit'=>2))
            ->addColumn('downpayment', 'integer', array('signed'=>false,'limit'=>4,'default'=>100))
            ->addColumn('guarantee', 'integer', array('signed'=>false,'limit'=>4,'default'=>0))
            ->addColumn('guaranteeperiod', 'integer', array('signed'=>false,'limit'=>2,'default'=>0))
            ->addColumn('carriagecharge', 'float')
            ->addColumn('returnnow', 'float')
            ->addColumn('time', 'datetime')
            ->addColumn('note', 'text', array('default'=>null))
            ->addColumn('stateid', 'integer', array('signed'=>false,'limit'=>4))
            ->addColumn('contract', 'integer', array('signed'=>false,'limit'=>1,'default'=>1))
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
