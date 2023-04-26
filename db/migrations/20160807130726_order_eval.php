<?php

use Phinx\Migration\AbstractMigration;

class OrderEval extends AbstractMigration
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
        $table = $this->table('order_eval', array('id' => false, 'primary_key' => array('orderid')));
        $table->addColumn('orderid',  'integer')
            ->addColumn('userid',  'integer')
            ->addColumn('time',  'datetime', array('default' => '1900-01-01 00:00:00'))
            ->addColumn('content',  'text')
            ->addColumn('award',  'integer',array('limit'=>4))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('order_eval');
    }
}
