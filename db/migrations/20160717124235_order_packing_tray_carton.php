<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class OrderPackingTrayCarton extends AbstractMigration
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
        $table = $this->table('orderpackingtraycarton');
        $table->addColumn('orderid',  'integer')
            ->addColumn('trayid',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('traynum',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('cartonid',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('cartonnum',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('numincarton',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('sumnum',  'integer')
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('orderpackingtraycarton');
    }
}
