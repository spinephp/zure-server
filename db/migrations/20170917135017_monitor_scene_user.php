<?php

use Phinx\Migration\AbstractMigration;

class MonitorSceneUser extends AbstractMigration
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
        $table = $this->table('monitorscene');
        $table->addColumn('sid', 'string', array('limit'=>8))
            ->addColumn('name', 'string', array('limit'=>20))
            ->addColumn('password', 'char', array('limit'=>47))
            ->addColumn('device', 'integer', array('default'=>0))
            ->addColumn('state',  'integer',array('default'=>0))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('monitorscene');
    }
}
