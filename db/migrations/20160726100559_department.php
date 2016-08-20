<?php

use Phinx\Migration\AbstractMigration;

class Department extends AbstractMigration
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
        $table = $this->table('department');
        $table->addColumn('name',  'char', array('limit' => 15))
            ->create();
        $names = array('董事长','经理','副经理','账务部','业务部','生产部','人事部');
        $rows=array();
        foreach($names as $key=>$name){
            $rows[$key] = array('id'=>$key+1,'name'=>$name); 
        }
        $this->insert('department', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('department');
    }
}
