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
        $rows = [
            ['id'=>1, 'name'=>'董事长'],
            ['id'=>2, 'name'=>'经理'],
            ['id'=>3, 'name'=>'副经理'],
            ['id'=>4, 'name'=>'账务部'],
            ['id'=>5, 'name'=>'业务部'],
            ['id'=>6, 'name'=>'技术部'],
            ['id'=>7, 'name'=>'生产部'],
            ['id'=>8, 'name'=>'人事部'],
        ];
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
