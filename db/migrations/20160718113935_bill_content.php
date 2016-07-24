<?php

use Phinx\Migration\AbstractMigration;

class BillContent extends AbstractMigration
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
        $table = $this->table('billcontent');
        $table->addColumn('name',  'string', array('limit' => 40))
            ->addColumn('name_en',  'string', array('limit' => 40))
            ->create();

        $rows = [
            ['id'=>1,'name'=>'明细','name_en'=>'Detailed'],
            ['id'=>2,'name'=>'耐火制品','name_en'=>' Refractory products'],
            ['id'=>3,'name'=>'耐火砖','name_en'=>'Refractory bricks'],
            ['id'=>4,'name'=>'耐火板','name_en'=>'Refractory board'],
        ];

        $this->insert('billcontent', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('billcontent');
    }
}
