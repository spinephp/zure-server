<?php

use Phinx\Migration\AbstractMigration;

class Transport extends AbstractMigration
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
        $table = $this->table('transport');
        $table->addColumn('name', 'string', array('limit'=>10))
            ->addColumn('name_en', 'string', array('limit'=>20))
            ->addColumn('note', 'string', array('limit'=>100))
            ->addColumn('charges', 'float', array('default'=>0))
           ->create();

        $rows = array(
            array('id'=>1, 'name'=>'自提', 'name_en'=>'Since', 'note'=>'由客户自己到云瑞提货。', 'charges'=>0),
            array('id'=>2, 'name'=>'代办托运', 'name_en'=>'Consignment agent', 'note'=>'由云瑞代理客户办理货物运输，费用由客户承担。', 'charges'=>0),
            array('id'=>3, 'name'=>'送货上门', 'name_en'=>'Home delivery', 'note'=>'货物由云瑞送到客户指定的地址，送货费用由云瑞承担。', 'charges'=>0)
        );

        $this->insert('transport', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('transport');
    }
}
