<?php

use Phinx\Migration\AbstractMigration;

class Post extends AbstractMigration
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
        $table = $this->table('post');
        $table->addColumn('name',  'string', array('limit'=>20))
            ->addColumn('departmentids',  'char', array('limit' => 20,'default'=>''))
            ->addColumn('right',  'integer', array('limit' => 32,'signed'=>false ,'default'=>0))
            ->create();
        $rows = array(
            array('id'=>1,'name'=>'董事长','departmentids'=>'02050607','right'=>0xffffffff),
            array('id'=>2,'name'=>'总经理','departmentids'=>'0304','right'=>0xffffffff),
            array('id'=>3,'name'=>'经营经理','departmentids'=>'','right'=>0xffffffff),
            array('id'=>4,'name'=>'生产经理','departmentids'=>'','right'=>0xffffffff),
            array('id'=>5,'name'=>'账务部长','departmentids'=>'04','right'=>0xffffffff),
            array('id'=>6,'name'=>'业务部长','departmentids'=>'05','right'=>0xffffffff),
            array('id'=>7,'name'=>'生产部长','departmentids'=>'06','right'=>0xffffffff),
            array('id'=>8,'name'=>'人事部长','departmentids'=>'07','right'=>0xffffffff),
        );
        $this->insert('post', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('post');
    }
}
