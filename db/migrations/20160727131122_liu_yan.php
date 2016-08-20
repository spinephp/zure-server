<?php

use Phinx\Migration\AbstractMigration;

class LiuYan extends AbstractMigration
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
        $table = $this->table('liuyan');
        $table->addColumn('name',  'string', array('limit' => 30))
            ->addColumn('company',  'string', array('limit' => 40))
            ->addColumn('address',  'string', array('limit' => 60))
            ->addColumn('title',  'string', array('limit' => 80))
            ->addColumn('email',  'string', array('limit' => 40))
            ->addColumn('tel',  'string', array('limit' => 15))
            ->addColumn('content',  'text')
            ->addColumn('time',  'date', array('default'=>'1900-01-01 00:00:00'))
            ->addColumn('ip',  'string', array('limit' => 15))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('liuyan');
    }
}
