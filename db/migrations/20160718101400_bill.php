<?php

use Phinx\Migration\AbstractMigration;

class Bill extends AbstractMigration
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
        $table = $this->table('bill');
        $table->addColumn('name',  'char', array('limit' => 40))
            ->addColumn('name_en',  'char', array('limit' => 40))
            ->create();

        $rows = [
            ['id'=>1,'name'=>'收据','name_en'=>'The receipt'],
            ['id'=>2,'name'=>'增值税专用发票','name_en'=>' VAT special invoice'],
            ['id'=>3,'name'=>'增值税普通发票','name_en'=>'VAT invoice'],
        ];

        $this->insert('bill', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('bill');
    }
}
