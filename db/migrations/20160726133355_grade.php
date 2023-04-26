<?php

use Phinx\Migration\AbstractMigration;

class Grade extends AbstractMigration
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
        $table = $this->table('grade');
        $table->addColumn('name',  'char', array('limit' => 4))
            ->addColumn('name_en',  'char', array('limit' => 10))
            ->addColumn('cost',  'integer', array('limit' => 6))
            ->addColumn('image',  'char', array('limit' => 10))
            ->addColumn('right',  'text')
            ->addColumn('right_en',  'text')
            ->addColumn('evalintegral',  'integer', array('limit' => 4))
            ->create();

        $rows = [
            ['id'=>1,'name'=>'注册','name_en'=>'register','cost'=>'0','image'=>'grade1.png','right'=>'可以享受注册会员级别所能购买的产品及服务； 
享受全场免运费服务； 
可以参与注册会员级别所能参与的商品拍卖； 
可享受返修取件运费优惠（查看返修说明）。 ','right_en'=>'Can enjoy the membership levels of register can buy products and services;
Enjoy full-free shipping service;
Can participate in iron membership level can participate in commodities auctions;
Enjoy a repair pickup shipping discount.','evalintegral'=>0],
            ['id'=>2,'name'=>'铜牌','name_en'=>'bronze','cost'=>'10000
','image'=>'grade2.png','right'=>'','right_en'=>'','evalintegral'=>10],
            ['id'=>3,'name'=>'银牌','name_en'=>'silver','cost'=>'50000
','image'=>'grade3.png','right'=>'','right_en'=>'','evalintegral'=>20],
            ['id'=>4,'name'=>'金牌','name_en'=>'gold','cost'=>'100000
','image'=>'grade4.png','right'=>'','right_en'=>'','evalintegral'=>30],
            ['id'=>5,'name'=>'钻石','name_en'=>'diamond','cost'=>'200000
','image'=>'grade5.png','right'=>'','right_en'=>'','evalintegral'=>40],
        ];

        $this->insert('grade', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('grade');
    }
}
