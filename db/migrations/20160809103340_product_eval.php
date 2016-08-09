<?php

use Phinx\Migration\AbstractMigration;

class ProductEval extends AbstractMigration
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
        $table = $this->table('producteval');
        $table->addColumn('proid', 'integer')
            ->addColumn('userid', 'integer')
            ->addColumn('label', 'integer', array('limit'=>4))
            ->addColumn('useideas', 'text')
            ->addColumn('star', 'integer', array('limit'=>4))
            ->addColumn('integral', 'integer', array('limit'=>4))
            ->addColumn('date', 'date')
            ->addColumn('useful', 'integer')
            ->addColumn('status', 'enum', array('values'=>['W', 'A', 'S']))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('producteval');
    }
}
