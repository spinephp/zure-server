<?php

use Phinx\Migration\AbstractMigration;

class MaterialType extends AbstractMigration
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
        $table = $this->table('materialtype');
        $table->addColumn('parentid',  'integer')
            ->addColumn('name',  'string', array('limit' => 40))
            ->addColumn('inrightid',  'integer', array('default' => 0))
            ->addColumn('outrightid',  'integer', array('default' => 0))
            ->addColumn('picture', 'char', array('limit'=>36,'default'=>''))
            ->addColumn('note',  'string', array('limit'=>100,'default' => ''))
            ->create();

        $rows = array(
            array('id'=>1, 'parentid'=>0, 'name'=>'原材料'),
            array('id'=>2, 'parentid'=>0, 'name'=>'燃料'),
            array('id'=>3, 'parentid'=>0, 'name'=>'包装材料'),
            array('id'=>4, 'parentid'=>0, 'name'=>'维修材料'),
            array('id'=>5, 'parentid'=>0, 'name'=>'办公用品'),
            array('id'=>6, 'parentid'=>0, 'name'=>'劳保用品'),
        );
        $this->insert('materialtype', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('materialtype');
    }
}
