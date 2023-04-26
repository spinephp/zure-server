<?php

use Phinx\Migration\AbstractMigration;

class ProductSharp extends AbstractMigration
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
        $table = $this->table('productsharp');
        $table->addColumn('name', 'string', array('limit'=>10))
            ->addColumn('name_en', 'string', array('limit'=>20))
           ->create();

        $rows = [
            ['id'=>1,'name'=>'立方体','name_en'=>'Cube'],
            ['id'=>2,'name'=>'半圆柱','name_en'=>'Half cylinder'],
            ['id'=>3,'name'=>'圆柱','name_en'=>'Cylinder'],
            ['id'=>4,'name'=>'圆管','name_en'=>'Round tube'],
            ['id'=>5,'name'=>'方管','name_en'=>'Square tube'],
            ['id'=>6,'name'=>'方台','name_en'=>'Square table'],
            ['id'=>7,'name'=>'圆台','name_en'=>'Round table'],
            ['id'=>8,'name'=>'特异型','name_en'=>'Special'],
        ];

        $this->insert('productsharp', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('productsharp');
    }
}
