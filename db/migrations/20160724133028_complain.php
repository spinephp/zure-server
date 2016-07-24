<?php

use Phinx\Migration\AbstractMigration;

class Complain extends AbstractMigration
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
        $table = $this->table('complain');
        $table->addColumn('name',  'string', array('limit' => 12))
            ->addColumn('name_en',  'string', array('limit' => 20))
            ->create();

        $rows = [
            ['id'=>1,'name'=>'产品相关','name_en'=>'product'],
            ['id'=>2,'name'=>'价格相关','name_en'=>'price'],
            ['id'=>3,'name'=>'服务相关','name_en'=>'service'],
            ['id'=>4,'name'=>'物流相关','name_en'=>'logistics flow'],
            ['id'=>5,'name'=>'售后相关','name_en'=>'after market'],
            ['id'=>6,'name'=>'财务相关','name_en'=>'finance'],
            ['id'=>7,'name'=>'活动相关','name_en'=>'activity'],
            ['id'=>8,'name'=>'网站相关','name_en'=>'website'],
            ['id'=>9,'name'=>'预约相关','name_en'=>'appointment'],
            ['id'=>10,'name'=>'其他方面','name_en'=>'other'],
        ];

        $this->insert('complain', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('complain');
    }
}
