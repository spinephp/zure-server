<?php

use Phinx\Migration\AbstractMigration;

class DepartmentRight extends AbstractMigration
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
        $this->dropTable('postright');
        
       // create order packing tray carton table
        $table = $this->table('departmentright');
        $table->addColumn('departmentid',  'integer')
            ->addColumn('name',  'string', array('limit'=>20,'default'=>''))
            ->addColumn('bit',  'integer', array('limit' => 32,'signed'=>false ,'default'=>0))
            ->addColumn('time',  'datetime', array('default'=>'1900-01-01 00:00:00'))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('departmentright');
    }
}
