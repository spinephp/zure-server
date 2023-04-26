<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Consignee extends AbstractMigration
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
        $table = $this->table('consignee');
        $table->addColumn('customid',  'integer')
            ->addColumn('name',  'string', array('limit' => 40))
            ->addColumn('country',  'integer', array('limit' => MysqlAdapter::INT_SMALL, 'default'=>48))
            ->addColumn('province', 'char', array('limit' => 2))
            ->addColumn('city',  'char', array('limit' => 2))
            ->addColumn('zone', 'char', array('limit' => 2))
            ->addColumn('address',  'string', array('limit' => 40))
            ->addColumn('email',  'string', array('limit' => 40))
            ->addColumn('mobile',  'char', array('limit' => 11))
            ->addColumn('tel',  'char', array('limit' => 18))
            ->addColumn('postcard',  'char', array('limit' => 10 ,'null'=>true))
            ->addIndex(array('customid'))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('consignee');
    }
}
