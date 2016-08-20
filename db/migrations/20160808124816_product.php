<?php

use Phinx\Migration\AbstractMigration;

class Product extends AbstractMigration
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
        $table = $this->table('product');
        $table->addColumn('classid', 'integer')
            ->addColumn('length', 'float')
            ->addColumn('width', 'float')
            ->addColumn('think', 'float')
            ->addColumn('unitlen', 'enum', array('values'=>['mm', '"'], 'default'=>'mm'))
            ->addColumn('unitwid', 'enum', array('values'=>['mm', '"'], 'default'=>'mm'))
            ->addColumn('unitthi', 'enum', array('values'=>['mm', '"'], 'default'=>'mm'))
            ->addColumn('picture', 'char', array('limit'=>36 ,'null'=>true,'default'=>NULL))
            ->addColumn('sharp', 'integer', array('limit'=>4, 'default'=>1))
            ->addColumn('unit', 'char', array('limit'=>4 ,'null'=>true,'default'=>NULL))
            ->addColumn('weight', 'float')
            ->addColumn('homeshow', 'enum', array('values'=>['Y', 'N'], 'default'=>'N'))
            ->addColumn('price', 'float')
            ->addColumn('returnnow', 'float', array('default'=>0))
            ->addColumn('evalintegral', 'integer', array('limit'=>4, 'default'=>2))
            ->addColumn('feelintegral', 'integer', array('limit'=>4, 'default'=>2))
            ->addColumn('amount', 'integer', array('limit'=>4, 'default'=>0))
            ->addColumn('cansale', 'enum', array('values'=>['Y', 'N'], 'default'=>'Y'))
            ->addColumn('physicoindex', 'integer', array('limit'=>4))
            ->addColumn('chemicalindex', 'integer', array('limit'=>4))
            ->addColumn('status', 'enum', array('values'=>['O', 'D', 'P', 'N'], 'default'=>'O'))
            ->addColumn('note', 'text', array('null'=>true,'default'=>NULL))
            ->addIndex(array('classid'))
            ->create();

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('product');
    }
}
