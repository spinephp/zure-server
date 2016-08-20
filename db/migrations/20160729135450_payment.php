<?php

use Phinx\Migration\AbstractMigration;

class Payment extends AbstractMigration
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
        $table = $this->table('payment');
        $table->addColumn('name',  'string', array('limit' => 40))
            ->addColumn('name_en',  'string', array('limit' => 60))
            ->addColumn('note',  'string', array('limit' => 100))
            ->addColumn('url',  'char', array('limit' => 20 ,'null'=>true ,'default'=>NULL))
            ->addColumn('urltext',  'char', array('limit' => 20 ,'null'=>true ,'default'=>NULL))
            ->create();

        $rows = [
            array('id'=>1, 'name'=>'在线支付', 'name_en'=>'Online payment', 'note'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡', 'url'=>NULL, 'urltext'=>NULL),
            array('id'=>2, 'name'=>'现金', 'name_en'=>'Cash', 'note'=>'订货后，客户直接到云瑞支付现金。', 'url'=>NULL, 'urltext'=>NULL),
            array('id'=>3, 'name'=>'公司转账', 'name_en'=>'Company transfer', 'note'=>'通过银行转账 转帐后1-3个工作日内到帐', 'url'=>NULL, 'urltext'=>NULL),
            array('id'=>4, 'name'=>'汇款', 'name_en'=>'Remittance', 'note'=>'通过银行或邮局汇款，汇款后1-3个工作日到账', 'url'=>NULL, 'urltext'=>NULL),
            array('id'=>5, 'name'=>'承兑汇票', 'name_en'=>'Acceptance bill', 'note'=>'客户用承兑汇票支付货款。', 'url'=>NULL, 'urltext'=>NULL)
        ];

        $this->insert('payment', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('paymant');
    }
}
