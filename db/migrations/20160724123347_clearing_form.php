<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class ClearingForm extends AbstractMigration
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
        $table = $this->table('clearingform');
        $table->addColumn('clearingform',  'string', array('limit' => 40))
            ->addColumn('down-payment',  'integer', array('limit' => MysqlAdapter::INT_TINY))
            ->addColumn('note',  'string', array('limit' => 100))
            ->create();

        $rows = [
            ['id'=>1,'clearingform'=>'全额预付','down-payment'=>100,'note'=>''],
            ['id'=>2,'clearingform'=>'部分预付','down-payment'=>30,'note'=>''],
            ['id'=>3,'clearingform'=>'货到付款','down-payment'=>0,'note'=>''],
            ['id'=>4,'clearingform'=>'带款提货','down-payment'=>0,'note'=>''],
            ['id'=>5,'clearingform'=>'信用证','down-payment'=>0,'note'=>''],
            ['id'=>6,'clearingform'=>'T/T','down-payment'=>0,'note'=>''],
        ];

        $this->insert('clearingform', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('clearingform');
    }
}
