<?php

use Phinx\Migration\AbstractMigration;

class OrderState extends AbstractMigration
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
        $table = $this->table('orderstate');
        $table->addColumn('name',  'string', array('limit' => 40))
            ->addColumn('name_en',  'string', array('limit' => 20))
            ->addColumn('actor',  'enum',array('values'=>['C', 'S']))
            ->addColumn('note',  'string', array('limit' => 40))
            ->addColumn('note_en',  'string', array('limit' => 100))
            ->addColumn('yrrnote',  'string', array('limit' => 40))
            ->addColumn('state',  'integer')
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('orderstate');
    }
}
