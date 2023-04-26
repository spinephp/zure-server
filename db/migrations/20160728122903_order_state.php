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
            ->addColumn('actor',  'enum',array('values'=>['C', 'S'] ,'default'=>'S'))
            ->addColumn('note',  'string', array('limit' => 40))
            ->addColumn('note_en',  'string', array('limit' => 100 ,'null'=>true ,'default'=>NULL))
            ->addColumn('yrrnote',  'string', array('limit' => 40))
            ->addColumn('state',  'integer',array('default'=>9))
            ->create();

        $rows = [
            array('id'=>1, 'name'=>'提交订单', 'name_en'=>'Submit order', 'actor'=>'C', 'note'=>'您提交了订单，请等待系统确认', 'note_en'=>'You have submitted the order, please wait for the confirmation of the system.', 'yrrnote'=>'客户提交了新订单，请尽快处理!!!', 'state'=>9),
            array('id'=>2, 'name'=>'签署合同', 'name_en'=>'sign a contract', 'actor'=>'S', 'note'=>'系统确认了订单，请您签署合同', 'note_en'=>'The system confirmed the order, please sign the contract', 'yrrnote'=>'订单已确认，等待客户签署合同', 'state'=>1),
            array('id'=>3, 'name'=>'预付款', 'name_en'=>'Advance', 'actor'=>'C', 'note'=>'系统等待你的预付款，请尽快办理', 'note_en'=>'System to wait for your advance payment, please handle', 'yrrnote'=>'等待预付款', 'state'=>0),
            array('id'=>4, 'name'=>'生产准备', 'name_en'=>'Arrange production', 'actor'=>'S', 'note'=>'系统正在进行生产准备', 'note_en'=>'The system is being prepared for production', 'yrrnote'=>'请尽快安排订单生产', 'state'=>9),
            array('id'=>5, 'name'=>'成型', 'name_en'=>'Shaped', 'actor'=>'S', 'note'=>'正在成型', 'note_en'=>'Pressing', 'yrrnote'=>'Pressing', 'state'=>0),
            array('id'=>6, 'name'=>'干燥', 'name_en'=>'Dried', 'actor'=>'S', 'note'=>'正在干燥', 'note_en'=>'Drying', 'yrrnote'=>'Drying', 'state'=>0),
            array('id'=>7, 'name'=>'烧成', 'name_en'=>'Fired', 'actor'=>'S', 'note'=>'正在烧成', 'note_en'=>'Burning', 'yrrnote'=>'Burning', 'state'=>0),
            array('id'=>8, 'name'=>'包装', 'name_en'=>'Packed', 'actor'=>'S', 'note'=>'正在包装', 'note_en'=>'Packaging', 'yrrnote'=>'Packaging', 'state'=>0),
            array('id'=>9, 'name'=>'支付货款', 'name_en'=>'Paid', 'actor'=>'C', 'note'=>'系统已准备好发货，请你尽快付款', 'note_en'=>'The system is ready for delivery, please make your payment as soon as possible.', 'yrrnote'=>'等待客户付款', 'state'=>9),
            array('id'=>10, 'name'=>'准备发货', 'name_en'=>'Shipped', 'actor'=>'S', 'note'=>'商品正在准备出库发运', 'note_en'=>'Goods are ready for shipment', 'yrrnote'=>'请尽快出库发运', 'state'=>9),
            array('id'=>11, 'name'=>'等待收货', 'name_en'=>'Waiting for receipt', 'actor'=>'S', 'note'=>'商品已发运，请准备收货', 'note_en'=>'The goods have been shipped. Please prepare the goods.', 'yrrnote'=>'等待客户收货', 'state'=>9),
            array('id'=>12, 'name'=>'付保证金', 'name_en'=>'Pay earnest money', 'actor'=>'C', 'note'=>'保证金支付成功', 'note_en'=>'Margin payment success', 'yrrnote'=>'', 'state'=>0),
            array('id'=>13, 'name'=>'完成', 'name_en'=>'Finished', 'actor'=>'S', 'note'=>'订单已经完成，感谢您购卖云瑞产品，欢迎您对本次交易及所购商品进行评价。', 'note_en'=>'Orders have been completed, thank you for the purchase of cloud products, welcome you to the transac', 'yrrnote'=>'订单已经完成', 'state'=>9),
            array('id'=>14, 'name'=>'已被取消', 'name_en'=>'Canceled', 'actor'=>'C', 'note'=>'订单已取消', 'note_en'=>'The order has been canceled', 'yrrnote'=>'订单已取消', 'state'=>10)
        ];

        $this->insert('orderstate', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('orderstate');
    }
}
