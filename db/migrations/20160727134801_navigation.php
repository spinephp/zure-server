<?php

use Phinx\Migration\AbstractMigration;

class Navigation extends AbstractMigration
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
        $table = $this->table('navigation');
        $table->addColumn('name',  'string', array('limit' => 40))
            ->addColumn('name_en',  'string', array('limit' => 40))
            ->addColumn('command',  'string', array('limit' => 50))
            ->create();

        $rows = [
            ['id'=>1,'name'=>'企业新闻','name_en'=>'News','command'=>'ShowNews'],
            ['id'=>2,'name'=>'产品中心','name_en'=>'Products','command'=>'ShowProducts'],
            ['id'=>3,'name'=>'联系我们','name_en'=>'Contact Us','command'=>'ShowContactUs'],
            ['id'=>4,'name'=>'我的云瑞','name_en'=>'My yunrui','command'=>'Member'],
            ['id'=>5,'name'=>'在线留言','name_en'=>'Leave word','command'=>'ShowLeaveMessage'],
        ];

        $this->insert('navigation', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('navigation');
    }
}
