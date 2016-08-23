<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Custom extends AbstractMigration
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
        $table = $this->table('custom');
        $table->addColumn('userid',  'integer')
            ->addColumn('type', 'enum', array('values'=>['P','U'] ,'default'=>'P'))
            ->addColumn('emailstate', 'enum', array('values'=>['Y','N'] ,'default'=>'N'))
            ->addColumn('mobilestate', 'enum', array('values'=>['Y','N'] ,'default'=>'N'))
            ->addColumn('accountstate', 'enum', array('values'=>['E','D'] ,'default'=>'E'))
            ->addColumn('ip',  'char', array('limit' => 20))
            ->addColumn('emailcode',  'char', array('limit' => 32 ,'null'=>true ,'default'=>NULL))
            ->addColumn('integral', 'integer', array('default'=>0))
            ->addIndex(array('userid'))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('custom');
    }
}
