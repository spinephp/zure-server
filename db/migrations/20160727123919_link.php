<?php

use Phinx\Migration\AbstractMigration;

class Link extends AbstractMigration
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
        $table = $this->table('link');
        $table->addColumn('name',  'string', array('limit' => 20))
            ->addColumn('name_en',  'string', array('limit' => 20))
            ->addColumn('url',  'string', array('limit' => 30))
            ->create();

        $rows = [
            ['id'=>1,'name'=>'百度一下','name_en'=>'Baidu','url'=>'http://www.baidu.com'],
            ['id'=>2,'name'=>'谷歌','name_en'=>'Google','url'=>'http://google.cn'],
            ['id'=>3,'name'=>'搜搜','name_en'=>'SOSO','url'=>'http://soso.com'],
            ['id'=>4,'name'=>'腾讯','name_en'=>'QQ','url'=>'http://qq.com'],
            ['id'=>5,'name'=>'网易','name_en'=>'NetEase','url'=>'http://163.com'],
            ['id'=>6,'name'=>'中国窑炉信息网','name_en'=>'KilnInfo','url'=>'http://www.kiln.org.cn'],
            ['id'=>7,'name'=>'中国耐火材料网','name_en'=>'NHCL','url'=>'http://www.nhcl.com.cn'],
        ];

        $this->insert('link', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('link');
    }
}
