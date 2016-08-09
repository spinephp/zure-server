<?php

use Phinx\Migration\AbstractMigration;

class BillSale extends AbstractMigration
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
        $table = $this->table('billsale');
        $table->addColumn('userid','integer')
            ->addColumn('name',  'string', array('limit' => 60))
            ->addColumn('name_en',  'string', array('limit' => 60))
            ->addColumn('address',  'string', array('limit' => 60))
            ->addColumn('tel',  'char', array('limit' => 16))
            ->addColumn('duty',  'char', array('limit' => 20))
            ->addColumn('bank',  'string', array('limit' => 40))
            ->addColumn('account',  'char', array('limit' => 16))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('billsale');
    }
}
