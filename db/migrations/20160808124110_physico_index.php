<?php

use Phinx\Migration\AbstractMigration;

class PhysicoIndex extends AbstractMigration
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
        $table = $this->table('physicoindex');
        $table->addColumn('name', 'char', array('limit'=>20))
            ->addColumn('name_en', 'char', array('limit'=>40))
            ->addColumn('unit', 'char', array('limit'=>20))
            ->addColumn('operator', 'char', array('limit'=>2))
            ->addColumn('environment', 'char', array('limit'=>10))
            ->addColumn('value', 'float')
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('physicoindex');
    }
}
