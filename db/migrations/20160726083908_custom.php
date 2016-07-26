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
            ->addColumn('type', 'enum', array('values'=>['P','U']))
            ->addColumn('emailstate', 'enum', array('values'=>['Y','N']))
            ->addColumn('mobilestate', 'enum', array('values'=>['Y','N']))
            ->addColumn('accountstate', 'enum', array('values'=>['E','D']))
            ->addColumn('ip',  'string', array('limit' => 20))
            ->addColumn('emailcode',  'string', array('limit' => 32))
            ->addColumn('integral', 'integer')
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
