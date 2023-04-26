<?php

use Phinx\Migration\AbstractMigration;

class Right extends AbstractMigration
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
        $table = $this->table('right');
        $table->addColumn('code', 'biginteger', array('signed'=>false,'limit'=>64))
            ->addColumn('name', 'string', array('limit'=>24))
           ->create();

        $data = array(
            '编辑站点信息',
            '查看站点信息',
            '编辑新闻信息',
            '查看新闻信息',
            '编辑留言',
            '查看留言',
            '编辑客户信息',
            '显示客户信息',
            '编辑产品',
            '查看产品',
            '编辑订单',
            '查看订单',
            '编辑材料',
            '查看材料',
            '编辑生产进度',
            '查看生产进度',
            '编辑雇员信息',
            '查看雇员信息',
            '监控干燥',
            '查看干燥记录',
        );

        $rows = array();

        for($i = 0;$i<31;++$i){
            $rows[$i]['id'] = $i+1;
            $rows[$i]['code'] = 0x80000000 >> $i;
            $rows[$i]['name'] = isset($data[$i])? $data[$i]:'未定义';
        }
        $rows[31]['id'] = 32;
        $rows[31]['code'] = 0x1;
        $rows[31]['name'] = '设置管理者权限';

        $this->insert('right', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('right');
    }
}
