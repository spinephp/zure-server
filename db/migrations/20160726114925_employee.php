<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Employee extends AbstractMigration
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
        $table = $this->table('employee');
        $table->addColumn('userid',  'integer')
            ->addColumn('departmentid',  'integer')
            ->addColumn('postids',  'char', array('limit' => 20,'default'=>''))
            ->addColumn('startdate',  'datetime', array('default'=>'1900-01-01 00:00:00'))
            ->addColumn('dateofbirth',  'date', array('null'=>true, 'default'=>NULL))
            ->addColumn('myright',  'integer', array('limit' => 32,'signed'=>false ,'default'=>0))
            ->create();
        $rows = array(
            array(
                'id'=>1,
                'userid'=>1,
                'departmentid'=>1,
                'postids'=>'01',
                'startdate'=>'2013-08-11 00:00:00',
                'dateofbirth'=>'1962-06-15',
                'myright'=>1933572097
            )
        );
        $this->insert('employee', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('employee');
    }
}
