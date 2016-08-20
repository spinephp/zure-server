<?php

use Phinx\Migration\AbstractMigration;

class Company extends AbstractMigration
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
        $table = $this->table('company');
        $table->addColumn('name',  'string', array('limit' => 200))
            ->addColumn('address',  'string', array('limit' => 200))
            ->addColumn('bank',  'string', array('limit' => 200))
            ->addColumn('account',  'char', array('limit' => 15))
            ->addColumn('email',  'string', array('limit' => 40))
            ->addColumn('www',  'string', array('limit' => 100))
            ->addColumn('tel',  'char', array('limit' => 18))
            ->addColumn('fax',  'char', array('limit' => 18))
            ->addColumn('postcard',  'char', array('limit' => 10))
            ->addColumn('duty',  'char', array('limit' => 18))
            ->create();

        $rows = [
            ['id'=>0,'name'=>'连云港云瑞耐火材料有限公司','address'=>'江苏连云港经济技术开发区泰山路12号','bank'=>'中行连云港开发区支行','account'=>'510558208802','email'=>'sales@yrr8.com','www'=>'http://www.yrr8.com','tel'=>'0518-85466356','fax'=>'0518-85466356','postcard'=>'222002','duty'=>'91320703670106912A']
        ];

        $this->insert('company', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('company');
    }
}
