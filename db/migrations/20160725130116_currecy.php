<?php

use Phinx\Migration\AbstractMigration;

class Currecy extends AbstractMigration
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
        $table = $this->table('currency');
        $table->addColumn('name',  'char', array('limit' => 20))
            ->addColumn('name_en',  'char', array('limit' => 20))
            ->addColumn('abbreviation',  'char', array('limit' => 3))
            ->addColumn('symbol',  'char', array('limit' => 1))
            ->addColumn('exchangerate', 'float')
            ->create();
        $rows = [
            ['id'=>1, 'name'=>'人民币', 'name_en'=>'Chinese Yuan', 'abbreviation'=>'CNY', 'symbol'=>'¥', 'exchangerate'=>1],
            ['id'=>2, 'name'=>'美元', 'name_en'=>'American dollar', 'abbreviation'=>'USD', 'symbol'=>'$', 'exchangerate'=>6.5],
        ];
        $this->insert('currency', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('currency');
    }
}
